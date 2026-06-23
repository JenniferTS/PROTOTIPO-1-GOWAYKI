<?php

namespace App\Providers;

use App\Models\User;
use App\Services\DestinoService;
use App\Services\ProgresoService;
use App\Services\RecorridoService;
use App\Services\RutaService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RutaService::class);
        $this->app->singleton(DestinoService::class);
        $this->app->singleton(RecorridoService::class);
        $this->app->singleton(ProgresoService::class);
    }

    public function boot(): void
    {
        Gate::define('admin', function (User $user) {
            return $user->email === config('gowayki.admin_email');
        });
    }
}
