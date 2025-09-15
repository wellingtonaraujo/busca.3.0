<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audit\Audit;
use App\Traits\PageHeaderTrait;

class AuditController extends Controller
{
    use PageHeaderTrait;

    public function __construct()
    {
        $this->initPageHeader();

        $this->breadcrumbs[] = ['label' => 'Admin', 'icon' => 'ti ti-settings'];
        $this->titulo = 'Auditoria do Sistema';

        // acrescentando botões
    }

    public function index(Request $request)
    {
        $logs = Audit::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->table, fn($q) => $q->where('table_name', $request->table))
            ->latest()
            ->paginate(20);

        return view('audit.index', compact('logs'))
            ->with('route', 'audit')
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons);
    }
}
