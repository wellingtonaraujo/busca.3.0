<?php

namespace App\Http\Controllers;

use App\Contracts\PageHeaderInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function breadcrumbs(): array
    {
        return [];
    }

    public function otherButtons(): array
    {
        return [];
    }
}
