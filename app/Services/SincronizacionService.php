<?php

namespace App\Services;

use App\Models\Ruta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SincronizacionService
{
    protected bool $simularFalloApi = false;

    public function simularFalloApi(bool $fallo): void
    {
        $this->simularFalloApi = $fallo;
    }

    public function sincronizar(): array
    {
        $actualizadas = 0;
        $rechazadas = 0;

        try {
            $datosExternos = $this->obtenerRutasDeApi();
        } catch (\Throwable $e) {
            Log::error('Fallo de API durante sincronización: ' . $e->getMessage(), ['exception' => $e]);
            return ['actualizadas' => 0, 'rechazadas' => 0, 'abortado' => true];
        }

        DB::beginTransaction();
        try {
            foreach ($datosExternos as $dato) {
                if ($this->esValido($dato)) {
                    Ruta::updateOrCreate(
                        ['nombre' => $dato['nombre']],
                        [
                            'descripcion'            => $dato['descripcion'] ?? null,
                            'origen'                 => $dato['origen'],
                            'destino'                => $dato['destino'],
                            'tiempo_estimado_minutos' => $dato['tiempo_estimado_minutos'],
                            'costo_aproximado_soles'  => $dato['costo_aproximado_soles'],
                            'color_linea'            => $dato['color_linea'] ?? '#E74C3C',
                            'activa'                 => true,
                        ]
                    );
                    $actualizadas++;
                } else {
                    $rechazadas++;
                    Log::warning('Dato de ruta rechazado por validación', [
                        'dato' => $dato,
                        'campos_faltantes' => $this->camposFaltantes($dato),
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error durante transacción de sincronización: ' . $e->getMessage(), ['exception' => $e]);
            return ['actualizadas' => 0, 'rechazadas' => 0, 'abortado' => true];
        }

        return [
            'actualizadas' => $actualizadas,
            'rechazadas' => $rechazadas,
            'abortado' => false,
        ];
    }

    protected function obtenerRutasDeApi(): array
    {
        if ($this->simularFalloApi) {
            throw new \RuntimeException('API no disponible (simulado)');
        }

        return config('gowayki.rutas_api_datos', []);
    }

    protected function esValido(array $dato): bool
    {
        return !empty($dato['nombre'])
            && !empty($dato['origen'])
            && !empty($dato['destino'])
            && isset($dato['tiempo_estimado_minutos'])
            && is_numeric($dato['tiempo_estimado_minutos'])
            && $dato['tiempo_estimado_minutos'] > 0
            && isset($dato['costo_aproximado_soles'])
            && is_numeric($dato['costo_aproximado_soles']);
    }

    protected function camposFaltantes(array $dato): array
    {
        $requeridos = ['nombre', 'origen', 'destino', 'tiempo_estimado_minutos', 'costo_aproximado_soles'];
        $faltantes = [];

        foreach ($requeridos as $campo) {
            if (empty($dato[$campo]) && !isset($dato[$campo])) {
                $faltantes[] = $campo;
            }
        }

        if (isset($dato['tiempo_estimado_minutos']) && (!is_numeric($dato['tiempo_estimado_minutos']) || $dato['tiempo_estimado_minutos'] <= 0)) {
            $faltantes[] = 'tiempo_estimado_minutos (inválido)';
        }

        if (isset($dato['costo_aproximado_soles']) && !is_numeric($dato['costo_aproximado_soles'])) {
            $faltantes[] = 'costo_aproximado_soles (inválido)';
        }

        return $faltantes;
    }
}
