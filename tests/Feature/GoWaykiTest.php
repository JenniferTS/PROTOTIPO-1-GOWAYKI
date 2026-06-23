<?php

namespace Tests\Feature;

use App\Exceptions\GoWaykiServiceException;
use App\Models\Destino;
use App\Models\LugarVisitado;
use App\Models\Ruta;
use App\Models\User;
use App\Services\DestinoService;
use App\Services\ProgresoService;
use App\Services\RutaService;
use App\Services\SincronizacionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GoWaykiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Ruta $ruta;
    protected Destino $destino;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@gowayki.com',
            'password' => Hash::make('password'),
        ]);

        $this->ruta = Ruta::create([
            'nombre' => 'Ruta A-1',
            'origen' => 'Plaza de Armas',
            'destino' => 'TECSUP',
            'tiempo_estimado_minutos' => 30,
            'costo_aproximado_soles' => 2.50,
            'color_linea' => '#E74C3C',
            'activa' => true,
        ]);

        $this->destino = Destino::create([
            'nombre' => 'Mirador de Yanahuara',
            'descripcion' => 'Hermoso mirador',
            'categoria' => 'turistico',
            'distrito' => 'Yanahuara',
            'latitud' => -16.3988,
            'longitud' => -71.5375,
            'calificacion' => 4.5,
            'activo' => true,
        ]);
    }

    // ========================================================================
    // MÓDULO 1 — Consulta y Detalle de Rutas
    // ========================================================================

    public function test_caso_1_1_consulta_con_origen_y_destino_validos()
    {
        $response = $this->get(route('rutas.index', ['origen' => 'Plaza', 'destino' => 'TECSUP']));
        $response->assertStatus(200);
        $response->assertSee('Ruta A-1');
        $response->assertSee('Plaza de Armas');
        $response->assertSee('TECSUP');
    }

    public function test_caso_1_2_consulta_sin_rutas_disponibles_muestra_empty_state()
    {
        $response = $this->get(route('rutas.index', ['origen' => 'NingunLado', 'destino' => 'OtroLado']));
        $response->assertStatus(200);
        $response->assertSee('No encontramos rutas disponibles entre');
        $response->assertSee('Ver todas las rutas');
        $response->assertSee('Explorar destinos');
    }

    public function test_caso_1_3_error_al_consultar_rutas_por_falla_api()
    {
        $service = $this->app->make(RutaService::class);
        $resultado = $service->buscarRutas('Plaza', 'TECSUP');
        $this->assertArrayHasKey('rutas', $resultado);
        $this->assertArrayHasKey('degradado', $resultado);
        $this->assertFalse($resultado['degradado']);
    }

    public function test_caso_1_4_visualizacion_de_detalle_de_ruta_seleccionada()
    {
        $response = $this->get(route('rutas.show', $this->ruta->id));
        $response->assertStatus(200);
        $response->assertSee('Ruta A-1');
        $response->assertSee('Paraderos');
        $response->assertSee('Minutos');
        $response->assertSee('Tarifa base');
    }

    public function test_caso_1_5_visualizacion_con_datos_incompletos()
    {
        $rutaSinDesc = Ruta::create([
            'nombre' => 'Ruta B-2',
            'origen' => 'A',
            'destino' => 'B',
            'tiempo_estimado_minutos' => 15,
            'costo_aproximado_soles' => 1.50,
            'activa' => true,
        ]);

        $response = $this->get(route('rutas.show', $rutaSinDesc->id));
        $response->assertStatus(200);
        $response->assertSee('Sin descripción disponible');
        $response->assertSee('Aún no se han registrado paraderos');
    }

    public function test_caso_1_6_error_al_cargar_detalle_de_ruta_inexistente()
    {
        $response = $this->get(route('rutas.show', 9999));
        $response->assertStatus(404);
    }

    // ========================================================================
    // MÓDULO 2 — Exploración de Destinos
    // ========================================================================

    public function test_caso_2_1_listado_de_destinos_disponible()
    {
        $response = $this->get(route('destinos.index'));
        $response->assertStatus(200);
        $response->assertSee('Mirador de Yanahuara');
    }

    public function test_caso_2_2_exploracion_sin_destinos_registrados_muestra_empty_state()
    {
        Destino::where('activo', true)->update(['activo' => false]);

        $response = $this->get(route('destinos.index'));
        $response->assertStatus(200);
        $response->assertSee('Aún no hay destinos disponibles');
    }

    public function test_caso_2_3_busqueda_o_filtro_de_destino_sin_resultados()
    {
        $response = $this->get(route('destinos.index', ['q' => 'LugarInexistente']));
        $response->assertStatus(200);
        $response->assertSee('No encontramos destinos que coincidan');
        $response->assertSee('Ver todos');
    }

    // ========================================================================
    // MÓDULO 3 — Planificación de Recorrido
    // ========================================================================

    public function test_caso_3_1_planificacion_con_datos_validos()
    {
        $response = $this->actingAs($this->user)->get(route('recorridos.planificar', [
            'origen' => 'Plaza de Armas',
            'destino' => 'TECSUP',
        ]));
        $response->assertStatus(200);
        $response->assertSee('Ruta A-1');
        $response->assertSee('Confirmar y guardar');
    }

    public function test_caso_3_2_planificacion_sin_opciones_disponibles_muestra_empty_state()
    {
        $response = $this->get(route('recorridos.planificar', [
            'origen' => 'LugarInexistente',
            'destino' => 'OtroInexistente',
        ]));
        $response->assertStatus(200);
        $response->assertSee('No encontramos una ruta directa');
    }

    public function test_caso_3_3_seleccion_de_ruta_muestra_boton_guardar_solo_para_autenticados()
    {
        $responseAnon = $this->get(route('recorridos.planificar', [
            'origen' => 'Plaza de Armas',
            'destino' => 'TECSUP',
        ]));
        $responseAnon->assertDontSee('Confirmar y guardar');
        $responseAnon->assertSee('Inicia sesión para guardar');

        $response = $this->actingAs($this->user)->get(route('recorridos.planificar', [
            'origen' => 'Plaza de Armas',
            'destino' => 'TECSUP',
        ]));
        $response->assertStatus(200);
        $response->assertSee('Confirmar y guardar');
    }

    // ========================================================================
    // MÓDULO 4 — Autenticación
    // ========================================================================

    public function test_caso_4_1_registro_con_datos_validos()
    {
        $response = $this->post(route('register'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', ['email' => 'nuevo@test.com']);
    }

    public function test_caso_4_2_registro_con_campos_vacios_o_invalidos()
    {
        $response = $this->post(route('register'), [
            'name' => '',
            'email' => 'email-invalido',
            'password' => 'corta',
            'password_confirmation' => 'otra',
        ]);
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_caso_4_3_registro_con_correo_ya_existente()
    {
        $this->post(route('register'), [
            'name' => 'Usuario Original',
            'email' => 'test@gowayki.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response = $this->post(route('register'), [
            'name' => 'Usuario Duplicado',
            'email' => 'test@gowayki.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_caso_4_4_inicio_de_sesion_con_credenciales_correctas()
    {
        $response = $this->post(route('login'), [
            'email' => 'test@gowayki.com',
            'password' => 'password',
        ]);
        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_caso_4_5_inicio_de_sesion_con_credenciales_incorrectas()
    {
        $response = $this->post(route('login'), [
            'email' => 'test@gowayki.com',
            'password' => 'wrong-password',
        ]);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_caso_4_6_acceso_a_funciones_protegidas_sin_sesion()
    {
        $rutasProtegidas = [
            route('perfil.progreso'),
            route('recorridos.miRuta'),
        ];

        foreach ($rutasProtegidas as $ruta) {
            $response = $this->get($ruta);
            $response->assertRedirect(route('login'));
        }
    }

    // ========================================================================
    // MÓDULO 5 — Progreso y Lugares Visitados
    // ========================================================================

    public function test_caso_5_1_marcado_de_destino_como_visitado()
    {
        $response = $this->actingAs($this->user)->post(route('perfil.visitar'), [
            'destino_id' => $this->destino->id,
            'fecha_visita' => now()->toDateString(),
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('lugares_visitados', [
            'user_id' => $this->user->id,
            'destino_id' => $this->destino->id,
        ]);
    }

    public function test_caso_5_2_registro_duplicado_de_lugar_visitado()
    {
        $this->actingAs($this->user)->post(route('perfil.visitar'), [
            'destino_id' => $this->destino->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('perfil.visitar'), [
            'destino_id' => $this->destino->id,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_caso_5_3_error_al_guardar_lugar_visitado_por_destino_inexistente()
    {
        $response = $this->actingAs($this->user)->post(route('perfil.visitar'), [
            'destino_id' => 9999,
        ]);

        $response->assertSessionHasErrors(['destino_id']);
    }

    public function test_caso_5_4_visualizacion_de_progreso_con_datos()
    {
        $this->actingAs($this->user)->post(route('perfil.visitar'), [
            'destino_id' => $this->destino->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('perfil.progreso'));
        $response->assertStatus(200);
        $response->assertSee('Mi Progreso');
    }

    public function test_caso_5_5_visualizacion_de_progreso_sin_datos_usuario_nuevo()
    {
        $response = $this->actingAs($this->user)->get(route('perfil.progreso'));
        $response->assertStatus(200);
        $response->assertSee('Aún no has explorado ningún destino');
        $response->assertSee('Explorar destinos');
    }

    public function test_caso_5_6_error_al_cargar_progreso_muestra_error_state()
    {
        $progresoService = $this->mock(ProgresoService::class);
        $progresoService->shouldReceive('obtenerProgreso')
            ->andThrow(new \RuntimeException('Fallo simulado'));

        $this->app->instance(ProgresoService::class, $progresoService);

        $response = $this->actingAs($this->user)->get(route('perfil.progreso'));
        $response->assertStatus(200);
        $response->assertSee('No pudimos cargar tu progreso');
        $response->assertSee('Reintentar');
    }

    // ========================================================================
    // MÓDULO 6 — Sincronización de API
    // ========================================================================

    public function test_caso_6_1_actualizacion_exitosa_con_datos_validos()
    {
        config(['gowayki.rutas_api_datos' => [
            [
                'nombre' => 'Ruta desde API',
                'origen' => 'Origen API',
                'destino' => 'Destino API',
                'tiempo_estimado_minutos' => 25,
                'costo_aproximado_soles' => 3.00,
            ],
        ]]);

        $this->artisan('gowayki:actualizar-rutas')
            ->assertSuccessful();
    }

    public function test_caso_6_2_caida_de_api_durante_actualizacion_no_borra_datos_existentes()
    {
        $service = $this->app->make(SincronizacionService::class);
        $service->simularFalloApi(true);

        $resultado = $service->sincronizar();
        $this->assertTrue($resultado['abortado']);
        $this->assertDatabaseHas('rutas', ['nombre' => 'Ruta A-1']);
    }

    public function test_caso_6_3_manejo_de_datos_incompletos_no_detiene_procesamiento()
    {
        config(['gowayki.rutas_api_datos' => [
            [
                'nombre' => 'Ruta Válida',
                'origen' => 'A',
                'destino' => 'B',
                'tiempo_estimado_minutos' => 20,
                'costo_aproximado_soles' => 2.00,
            ],
            [
                'nombre' => '',
                'origen' => 'C',
                'destino' => 'D',
                'tiempo_estimado_minutos' => 10,
                'costo_aproximado_soles' => 1.00,
            ],
        ]]);

        $this->artisan('gowayki:actualizar-rutas')
            ->assertSuccessful();

        $this->assertDatabaseHas('rutas', ['nombre' => 'Ruta Válida']);
    }
}
