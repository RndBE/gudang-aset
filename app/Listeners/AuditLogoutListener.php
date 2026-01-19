<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Services\AuditLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuditLogoutListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        $user = $event->user;
        $instansiId = $user?->instansi_id ?? null;

        if (!$instansiId) return;

        AuditLogger::log(
            $instansiId,
            'logout',
            'pengguna',
            $user->id,
            'auth',
            null,
            null,
            ['id' => $user->id, 'nama' => $user->nama_lengkap ?? $user->name ?? null]
        );
    }
}
