<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use App\Models\Audit\Audit;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Criado
        Event::listen('eloquent.created: *', function ($event, $models) {
            $model = $models[0];
            $this->logAction('create', $model, null, $model->getAttributes());
        });

        // Atualizado
        Event::listen('eloquent.updated: *', function ($event, $models) {
            $model = $models[0];

            // Apenas os campos alterados
            $changes = $model->getDirty();

            // Captura os valores antigos apenas dos campos alterados
            $original = collect($changes)
                ->mapWithKeys(fn($value, $field) => [$field => $model->getOriginal($field)])
                ->toArray();

            $this->logAction('update', $model, $original, $changes);
        });

        // Deletado
        Event::listen('eloquent.deleted: *', function ($event, $models) {
            $model = $models[0];
            $this->logAction('delete', $model, $model->getOriginal(), null);
        });
    }

    protected function logAction($action, $model, $original = null, $changes = null)
    {
        // Evita loop infinito de logs
        if ($model instanceof Audit) {
            return;
        }

        // Registra log apenas após commit da transação
        DB::afterCommit(function () use ($action, $model, $original, $changes) {
            Audit::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'table_name' => $model->getTable(),
                'record_id' => $model->getKey(),
                'old_data' => $original ? json_encode($original) : null,
                'new_data' => $changes ? json_encode($changes) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);
        });
    }
}
