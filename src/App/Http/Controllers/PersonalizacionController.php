<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Icebearsoft\Kitukizuri\App\Models\Personalizacion;

class PersonalizacionController extends Controller
{
    /**
     * index
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $personalizacion = Personalizacion::first();

        if (empty($personalizacion)) {
            $personalizacion = new Personalizacion([
                'primary_color' => '#6576ff',
                'secondary_color' => '#0d7195',
                'accent_color' => '#b3c2ff',
                'surface_color' => '#f5f6fa',
            ]);
        }

        $view = config('kitukizuri.prevUi') == true ? 'kitukizuri_prev' : 'kitukizuri';

        return view($view.'::personalizacion', [
            'layout' => 'krud::layout',
            'titulo' => 'Personalizacion',
            'dash' => true,
            'kmenu' => true,
            'personalizacion' => $personalizacion,
        ]);
    }

    /**
     * store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'primary_color' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'secondary_color' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'surface_color' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
        ]);

        $data = array_map('strtolower', $data);

        $personalizacion = Personalizacion::first();
        if (empty($personalizacion)) {
            $personalizacion = new Personalizacion;
        }

        $personalizacion->fill($data);
        $personalizacion->save();

        return redirect()
            ->route('personalizacion.index')
            ->with('status', 'Personalizacion guardada correctamente.');
    }
}
