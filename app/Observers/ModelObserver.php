<?php
<?php

namespace App\Observers;

use App\Models\UserActionLog;

class ModelObserver
{
    public function created($model)
    {
        $this->registrar('create', $model, null, $model->getAttributes());
    }

    public function updated($model)
    {
        $this->registrar('update', $model, $model->getOriginal(), $model->getDirty());
    }

    public function deleted($model)
    {
        $this->registrar('delete', $model, $model->getOriginal(), null);
    }

    private function registrar($acao, $model, $antes = null, $depois = null)
    {
        UserActionLog::create([
            'user_id'    => auth()->id(),
            'action'     => $acao,
            'table_name' => $model->getTable(),
            'record_id'  => $model->id ?? null,
            'old_data'   => $antes,
            'new_data'   => $depois,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
