<?php

namespace App\Traits;

trait PageHeaderTrait
{
    protected $buttons = [];
    protected $breadcrumbs = [];
    protected $titulo;

    public function initPageHeader()
    {
        // inicialização da trait
        $this->breadcrumbs[] = ['label' => 'Home', 'link' => route('home'), 'icon' => 'ti ti-home'];
    }
}
