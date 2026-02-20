<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.index');
    }

    public function backup()
    {
        try {
            Artisan::call('db:backup');
            return redirect()->back()->with('success', 'El respaldo de la base de datos se ha creado exitosamente.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un error al crear el respaldo: ' . $e->getMessage());
        }
    }

    public function restore()
    {
        try {
            Artisan::call('db:restore');
            return redirect()->back()->with('success', 'La base de datos ha sido restaurada existosamente al último respaldo.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un error al restaurar la base de datos: ' . $e->getMessage());
        }
    }
}
