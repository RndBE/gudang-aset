<?php

namespace App\Observers;

use App\Models\Aset;
use App\Services\AuditLogger;

class AsetObserver
{
    /**
     * Handle the Aset "created" event.
     */
    public function created(Aset $m): void
    {
        AuditLogger::log($m->instansi_id, 'tambah', $m->getTable(), $m->id, 'aset', $m->id, null, $m->toArray());
    }

    /**
     * Handle the Aset "updated" event.
     */
    public function updated(Aset $m): void
    {
        $changes = $m->getChanges();
        if (!$changes) return;

        AuditLogger::log($m->instansi_id, 'ubah', $m->getTable(), $m->id, 'aset', $m->id, $m->getOriginal(), $m->toArray());
    }

    /**
     * Handle the Aset "deleted" event.
     */
    public function deleted(Aset $m): void
    {
        AuditLogger::log($m->instansi_id, 'hapus', $m->getTable(), $m->id, 'aset', $m->id, $m->toArray(), null);
    }

    /**
     * Handle the Aset "restored" event.
     */
    public function restored(Aset $aset): void
    {
        //
    }

    /**
     * Handle the Aset "force deleted" event.
     */
    public function forceDeleted(Aset $aset): void
    {
        //
    }
}
