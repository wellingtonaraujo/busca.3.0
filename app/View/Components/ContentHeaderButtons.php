<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContentHeaderButtons extends Component
{
    // propriedades
    public $bgCollor;
    public $hoverBgCollor;
    public $textCollor;
    public $icon;
    public $route;
    public $title;

    /**
     * Create a new component instance.
     */
    public function __construct($bgCollor=null, $hoverBgCollor=null, $textCollor=null, $icon=null, $route=null, $title=null)
    {
        $this->bgCollor = $bgCollor;
        $this->hoverBgCollor = $hoverBgCollor;
        $this->textCollor = $textCollor;
        $this->icon = $icon;
        $this->route = $route;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.content-header-buttons');
    }
}
