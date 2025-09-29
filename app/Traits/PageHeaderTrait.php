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
        $this->breadcrumbs[] = ['label' => 'Home', 'link' => route('search'), 'icon' => 'ti ti-home'];
    }
}
