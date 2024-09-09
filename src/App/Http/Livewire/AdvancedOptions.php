<?php

namespace Icebearsoft\Kitukizuri\App\Http\Livewire;

use Livewire\Component;

class AdvancedOptions extends Component
{
    /**
     * Indicates if show modal.
     *
     * @var bool
     */
    public $displayingAdvancedOptions = false;
    

    public function showAdvancedOption()
    {
        $this->displayingAdvancedOptions = true;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('kitukizuri::dashboard.advancedoptions');
    }
}
