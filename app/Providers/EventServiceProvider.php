<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\AuditLoginListener::class,
        ],
        \Illuminate\Auth\Events\Logout::class => [
            \App\Listeners\AuditLogoutListener::class,
        ],
        // \Illuminate\Auth\Events\Failed::class => [
        //     \App\Listeners\AuditLoginFailedListener::class,
        // ],
    ];

    public function boot(): void {}
}
