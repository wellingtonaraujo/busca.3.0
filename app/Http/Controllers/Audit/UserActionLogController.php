<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Audit\UserActionLog;
use App\Traits\PageHeaderTrait;
use Illuminate\Http\Request;

class UserActionLogController extends Controller
{
    use PageHeaderTrait;

    public function __construct()
    {
        $this->initPageHeader();

        $this->breadcrumbs[] = ['label' => 'Admin', 'icon' => 'ti ti-settings'];
        // $this->breadcrumbs[] = ['label' => 'Auditoria', 'link' => route('audit.index'), 'icon' => 'ti ti-id'];

        switch (request()->route()->getActionMethod()) {
            // case 'edit':
            //     $this->titulo = 'Alterar Controle de Acesso';
            //     $this->breadcrumbs[] = ['label' => 'Acl', 'link' => route('acl.index'), 'icon' => 'ti ti-key'];
            //     break;
            // case 'create':
            //     $this->titulo = 'Novo Controle de Acesso';
            //     $this->breadcrumbs[] = ['label' => 'Acl', 'link' => route('acl.index'), 'icon' => 'ti ti-key'];
            //     break;

            default:
                $this->titulo = 'Auditoria do Sistema';
                break;
        }

        // acrescentando botões
        // $this->buttons[] = ['icon' => 'ti ti-plus', 'link' => route('acl.create'),'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Novo registro'];
    }

    public function index(Request $request)
    {
        $logs = UserActionLog::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->table, fn($q) => $q->where('table_name', $request->table))
            ->latest()
            ->paginate(20);

        return view('audit.index', compact('logs'))
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons);
    }
}
