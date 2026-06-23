<?php

namespace App\Console\Commands;

use App\Services\SincronizacionService;
use Illuminate\Console\Command;

class ActualizarRutasCommand extends Command
{
    protected $signature = 'gowayki:actualizar-rutas';
    protected $description = 'Sincroniza información de rutas desde el proveedor de datos (TRUFI/API)';

    public function handle(SincronizacionService $service): int
    {
        $this->info('Iniciando sincronización de rutas...');

        $resultado = $service->sincronizar();

        if ($resultado['abortado']) {
            $this->error('Sincronización abortada por fallo de conexión o error interno.');
            return self::FAILURE;
        }

        $this->info("Rutas actualizadas: {$resultado['actualizadas']}");
        $this->info("Rutas rechazadas: {$resultado['rechazadas']}");

        return self::SUCCESS;
    }
}
