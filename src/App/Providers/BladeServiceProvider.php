<?php

namespace Icebearsoft\Kitukizuri\App\Providers;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views/krud', 'krud');
        $this->loadViewsFrom(__DIR__.'/resources/views/kitukizuri', 'kitukizuri');

        $this->loadViewsFrom(__DIR__.'/resources/views/krud_prev', 'krud_prev');
        $this->loadViewsFrom(__DIR__.'/resources/views/kitukizuri_prev', 'kitukizuri_prev');

        $this->callAfterResolving(BladeCompiler::class, function () {
            $components = [
                'input',
                'table',
                'title',
                'select',
                'select2',
                'password',
                'textarea',
                'daterange',
                'index-tree'
            ];
            
            foreach ($components as $component) {
                $this->registerComponent($component);
            }
            
        });

        $this->registerLivewireComponent([
            'krud.advancedOptions' => 'AdvancedOptions'
        ]);
    }

    /**
     * registerComponent
     *
     * @param  mixed $component
     * @return void
     */
    protected function registerComponent($component)
    {
        Blade::component('krud::components.'.$component, 'krud-'.$component);
        Blade::component('krud_prev::components.'.$component, 'krud-prev-'.$component);
    }
    
    /**
     * registerLivewireComponent
     *
     * @param  mixed $components
     * @return void
     */
    protected function registerLivewireComponent($components)
    {
        if(class_exists(\Livewire\Livewire::class)){
            $namespace = 'Icebearsoft\\Kitukizuri\\App\\Http\\Livewire\\';
            foreach ($components as $alias => $className) {
                Livewire::component($alias, $namespace.$className);
            }
        }
    }
}