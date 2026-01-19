<?php

namespace App\Observers;

use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        $u = auth()->user();
        if (!$u) return;

        AuditLogger::log(
            $u->instansi_id,
            'tambah',
            $model->getTable(),
            $model->getKey(),
            null,
            null,
            null,
            $model->toArray(),
            $u->id
        );
    }

    public function updated(Model $model): void
    {
        $u = auth()->user();
        if (!$u) return;

        AuditLogger::log(
            $u->instansi_id,
            'ubah',
            $model->getTable(),
            $model->getKey(),
            null,
            null,
            $model->getOriginal(),
            $model->getChanges(),
            $u->id
        );
    }

    public function deleted(Model $model): void
    {
        $u = auth()->user();
        if (!$u) return;

        AuditLogger::log(
            $u->instansi_id,
            'hapus',
            $model->getTable(),
            $model->getKey(),
            null,
            null,
            $model->toArray(),
            null,
            $u->id
        );
    }
}
