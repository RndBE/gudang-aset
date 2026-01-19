<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Observers\AsetObserver;
use App\Observers\AuditObserver;

use App\Models\Aset;
use App\Models\Barang;
use App\Models\PeminjamanAset;
use App\Models\PenugasanAset;
use App\Models\PenghapusanAset;
use App\Models\PesananPembelian;
use App\Models\Penerimaan;
use App\Models\Permintaan;
use App\Models\Pengeluaran;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        // Aset::observe(AsetObserver::class);
        Aset::observe(AuditObserver::class);
        Barang::observe(AuditObserver::class);
        PeminjamanAset::observe(AuditObserver::class);
        PenugasanAset::observe(AuditObserver::class);
        PenghapusanAset::observe(AuditObserver::class);
        PesananPembelian::observe(AuditObserver::class);
        Penerimaan::observe(AuditObserver::class);
        Permintaan::observe(AuditObserver::class);
        Pengeluaran::observe(AuditObserver::class);
    }
}
