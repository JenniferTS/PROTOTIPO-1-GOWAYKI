# Matriz QA — GoWayki

| Módulo | Caso | Estado | Archivo de Test | Observaciones |
|---|---|---|---|---|
| 1 | 1.1 — Consulta con origen y destino válidos | ✅ Conforme | `GoWaykiTest::test_caso_1_1_consulta_con_origen_y_destino_validos` | Valida colección no vacía, nombre, origen y destino visibles |
| 1 | 1.2 — Consulta sin rutas disponibles | ✅ Conforme | `GoWaykiTest::test_caso_1_2_consulta_sin_rutas_disponibles_muestra_empty_state` | Empty state con mensaje específico y CTA dual |
| 1 | 1.3 — Error al consultar rutas por falla de API | ✅ Conforme | `GoWaykiTest::test_caso_1_3_error_al_consultar_rutas_por_falla_api` | `buscarRutas()` retorna `{rutas, degradado}`; degraded=false en éxito |
| 1 | 1.4 — Visualización de detalle de ruta | ✅ Conforme | `GoWaykiTest::test_caso_1_4_visualizacion_de_detalle_de_ruta_seleccionada` | Muestra nombre, paraderos, minutos, tarifa |
| 1 | 1.5 — Visualización con datos incompletos | ✅ Conforme | `GoWaykiTest::test_caso_1_5_visualizacion_con_datos_incompletos` | Null-safe, fallbacks visibles sin errores Blade |
| 1 | 1.6 — Error al cargar detalle de ruta | ✅ Conforme | `GoWaykiTest::test_caso_1_6_error_al_cargar_detalle_de_ruta_inexistente` | ID inexistente → abort(404) con página personalizada |
| 2 | 2.1 — Listado de destinos disponible | ✅ Conforme | `GoWaykiTest::test_caso_2_1_listado_de_destinos_disponible` | Grid con cards, nombre visible |
| 2 | 2.2 — Exploración sin destinos registrados | ✅ Conforme | `GoWaykiTest::test_caso_2_2_exploracion_sin_destinos_registrados_muestra_empty_state` | Empty state con mensaje contextual |
| 2 | 2.3 — Búsqueda o filtro sin resultados | ✅ Conforme | `GoWaykiTest::test_caso_2_3_busqueda_o_filtro_de_destino_sin_resultados` | Empty state específico + botón "Ver todos" |
| 3 | 3.1 — Planificación con datos válidos | ✅ Conforme | `GoWaykiTest::test_caso_3_1_planificacion_con_datos_validos` | Cards de rutas visibles, botón guardar presente |
| 3 | 3.2 — Planificación sin opciones disponibles | ✅ Conforme | `GoWaykiTest::test_caso_3_2_planificacion_sin_opciones_disponibles_muestra_empty_state` | Empty state con mensaje y CTAs |
| 3 | 3.3 — Selección de ruta: guardar solo autenticados | ✅ Conforme | `GoWaykiTest::test_caso_3_3_seleccion_de_ruta_muestra_boton_guardar_solo_para_autenticados` | Botón visible con sesión, oculto sin sesión |
| 4 | 4.1 — Registro con datos válidos | ✅ Conforme | `GoWaykiTest::test_caso_4_1_registro_con_datos_validos` | Crea usuario + autentica + redirige a home |
| 4 | 4.2 — Registro con campos vacíos o inválidos | ✅ Conforme | `GoWaykiTest::test_caso_4_2_registro_con_campos_vacios_o_invalidos` | Errores en name, email, password |
| 4 | 4.3 — Registro con correo ya existente | ✅ Conforme | `GoWaykiTest::test_caso_4_3_registro_con_correo_ya_existente` | unique:users.email → session error |
| 4 | 4.4 — Inicio de sesión con credenciales correctas | ✅ Conforme | `GoWaykiTest::test_caso_4_4_inicio_de_sesion_con_credenciales_correctas` | Auth::attempt exitoso → redirect->intended |
| 4 | 4.5 — Inicio de sesión con credenciales incorrectas | ✅ Conforme | `GoWaykiTest::test_caso_4_5_inicio_de_sesion_con_credenciales_incorrectas` | Mensaje genérico, no repopula password |
| 4 | 4.6 — Acceso a funciones protegidas sin sesión | ✅ Conforme | `GoWaykiTest::test_caso_4_6_acceso_a_funciones_protegidas_sin_sesion` | Redirección a route('login') |
| 5 | 5.1 — Marcado de destino como visitado | ✅ Conforme | `GoWaykiTest::test_caso_5_1_marcado_de_destino_como_visitado` | Flash success + registro en BD |
| 5 | 5.2 — Registro duplicado de lugar visitado | ✅ Conforme | `GoWaykiTest::test_caso_5_2_registro_duplicado_de_lugar_visitado` | GoWaykiServiceException → flash error |
| 5 | 5.3 — Error al guardar lugar visitado | ✅ Conforme | `GoWaykiTest::test_caso_5_3_error_al_guardar_lugar_visitado_por_destino_inexistente` | Flash error sin perder datos |
| 5 | 5.4 — Visualización de progreso con datos | ✅ Conforme | `GoWaykiTest::test_caso_5_4_visualizacion_de_progreso_con_datos` | Barra progreso + porcentaje + lista |
| 5 | 5.5 — Visualización de progreso sin datos | ✅ Conforme | `GoWaykiTest::test_caso_5_5_visualizacion_de_progreso_sin_datos_usuario_nuevo` | Empty state con CTA a destinos |
| 5 | 5.6 — Error al cargar progreso | ✅ Conforme | `GoWaykiTest::test_caso_5_6_error_al_cargar_progreso_muestra_error_state` | Vista con errorCarga + botón Reintentar |
| 6 | 6.1 — Actualización exitosa | ✅ Conforme | `GoWaykiTest::test_caso_6_1_actualizacion_exitosa_con_datos_validos` | Artisan command ejecuta sin errores |
| 6 | 6.2 — Caída de API durante actualización | ✅ Conforme | `GoWaykiTest::test_caso_6_2_caida_de_api_durante_actualizacion_no_borra_datos_existentes` | Aborta sin tocar BD, datos previos intactos |
| 6 | 6.3 — Manejo de datos incompletos | ✅ Conforme | `GoWaykiTest::test_caso_6_3_manejo_de_datos_incompletos_no_detiene_procesamiento` | Registros válidos se guardan, inválidos se rechazan + loguean |
