<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectTw extends Component
{
    public $name;
    public $id;
    public $options;
    public $placeholder;
    public $title;
    public $required;
    public $selected;

    public function __construct($name, $id = null, $options = null, $selected= null, $placeholder = null, $title = null, $required = null)
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->options = empty($options) ? array(1=>'SIM', 2=>'NÃO') : $options;
        $this->placeholder = $placeholder;
        $this->title = $title;
        $this->required = $required;
        $this->selected = $selected;
    }

    public function render()
    {
        return view('components.select-tw');
    }
}
