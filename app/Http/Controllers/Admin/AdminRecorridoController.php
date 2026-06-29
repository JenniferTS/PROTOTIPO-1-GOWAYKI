<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use Illuminate\Contracts\View\View;

class AdminRecorridoController extends Controller
{
    public function index(): View
    {
        $recorridos = Recorrido::with('user')->latest()->take(100)->get();
        return view('admin.recorridos.index', compact('recorridos'));
    }
}
