<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputTw extends Component
{
    // propriedades do component
    public $type;
    public $name;
    public $id;
    public $value;
    public $placeholder;
    public $title;
    public $required;
    /**
     * Create a new component instance.
     */
    public function __construct($type, $name, $id = null, $value = null, $placeholder = null, $title = null, $required = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->title = $title;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
        public function render()
    {
        return view('components.input-tw');
    }
}
