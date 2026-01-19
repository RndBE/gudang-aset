<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\AuditLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuditLoginListener
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
    public function handle(Login $event): void
    {
        $user = $event->user;
        if (!$user?->instansi_id) return;

        AuditLogger::log(
            $user->instansi_id,
            'login',
            'pengguna',
            $user->id,
            'auth',
            null,
            null,
            ['id' => $user->id, 'nama' => $user->nama_lengkap ?? $user->name ?? null]
        );
    }
}
