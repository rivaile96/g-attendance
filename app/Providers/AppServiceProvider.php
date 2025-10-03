<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade; // 1. Tambahkan import Blade

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
        Paginator::defaultView('vendor.pagination.tailwind');

        // ▼▼▼ 2. TAMBAHKAN BLOK KODE INI ▼▼▼
        /**
         * Membuat custom Blade directive @admin.
         * Ini akan mengecek apakah user yang sedang login adalah admin.
         */
        Blade::if('admin', function () {
            return \Auth::check() && \Auth::user()->is_admin;
        });
        // ▲▲▲ --------------------------------- ▲▲▲
    }
}