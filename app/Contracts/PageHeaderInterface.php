<?php

namespace App\Contracts;

interface PageHeaderInterface
{
    public function breadcrumbs(): array;
    public function otherButtons(): array;
}
