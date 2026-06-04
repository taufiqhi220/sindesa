<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;

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
        \Carbon\Carbon::setLocale('id');

        // Share unreadCount to all operator views
        \Illuminate\Support\Facades\View::composer('operator.*', function ($view) {
            $unreadCount = \App\Models\PengajuanSurat::where('status', 'menunggu_verifikasi')
                                         ->where('is_seen_by_operator', false)
                                         ->count();
            $view->with('unreadCount', $unreadCount);
        });

        // Share countPerluTtd to all kades views
        \Illuminate\Support\Facades\View::composer('kades.*', function ($view) {
            $countPerluTtd = \App\Models\PengajuanSurat::where('status', 'diproses_kades')->count();
            $unreadCountKades = \App\Models\PengajuanSurat::where('status', 'diproses_kades')
                                           ->where('is_seen_by_kades', false)
                                           ->count();
            $view->with('countPerluTtd', $countPerluTtd)
                 ->with('unreadCountKades', $unreadCountKades);
        });
    }
}
