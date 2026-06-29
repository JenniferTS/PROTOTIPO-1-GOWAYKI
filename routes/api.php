<?php

use App\Http\Controllers\Api\RutaCortaController;
use Illuminate\Support\Facades\Route;

Route::get('/ruta-corta', RutaCortaController::class);
