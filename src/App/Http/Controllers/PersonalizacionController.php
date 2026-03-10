<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $defaults = kitukizuriPersonalizacionDefaults();

        if (empty($personalizacion)) {
            $defaults['primary_skin'] = 'default';
            $personalizacion = new Personalizacion($defaults);
        } else {
            foreach ($defaults as $field => $value) {
                if (!isset($personalizacion->{$field}) || $personalizacion->{$field} === '') {
                    $personalizacion->{$field} = $value;
                }
            }
        }

        $view = config('kitukizuri.prevUi') == true ? 'kitukizuri_prev' : 'kitukizuri';

        return view($view.'::personalizacion', [
            'layout' => 'krud::layout',
            'titulo' => 'Personalizacion',
            'dash' => true,
            'kmenu' => true,
            'personalizacion' => $personalizacion,
            'layoutOptions' => $this->layoutOptions(),
            'skinPresetLabels' => $this->skinPresetLabels(),
            'skinPresetColors' => kitukizuriThemeSkinPresets(),
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
        $skinPresets = kitukizuriThemeSkinPresets();
        $allowedSkins = array_merge(['custom'], array_keys($skinPresets));

        $data = $request->validate([
            'primary_color' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'secondary_color' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'surface_color' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'direction' => ['required', Rule::in(['ltr', 'rtl'])],
            'ui_style' => ['required', Rule::in(['default', 'bordered'])],
            'sidebar_style' => ['required', Rule::in(['auto', 'white', 'dark', 'theme'])],
            'skin_mode' => ['required', Rule::in(['light', 'dark'])],
            'primary_skin' => ['required', Rule::in($allowedSkins)],
        ]);

        if ($data['primary_skin'] !== 'custom' && array_key_exists($data['primary_skin'], $skinPresets)) {
            $data = array_merge($data, $skinPresets[$data['primary_skin']]);
        }

        foreach (['primary_color', 'secondary_color', 'accent_color', 'surface_color'] as $colorField) {
            $data[$colorField] = strtolower((string) $data[$colorField]);
        }

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

    protected function layoutOptions(): array
    {
        return [
            'direction' => [
                'ltr' => 'LTR Mode',
                'rtl' => 'RTL Mode',
            ],
            'ui_style' => [
                'default' => 'Default',
                'bordered' => 'Bordered',
            ],
            'sidebar_style' => [
                'auto' => 'Auto',
                'white' => 'White',
                'dark' => 'Dark',
                'theme' => 'Theme',
            ],
            'skin_mode' => [
                'light' => 'Light Skin',
                'dark' => 'Dark Skin',
            ],
        ];
    }

    protected function skinPresetLabels(): array
    {
        return [
            'default' => 'Default',
            'blue_light' => 'Blue Light',
            'egyptian' => 'Egyptian',
            'purple' => 'Purple',
            'green' => 'Green',
            'red' => 'Red',
            'custom' => 'Custom',
        ];
    }
}
