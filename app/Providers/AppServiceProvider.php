<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;


use Illuminate\Support\Facades\Request;

use Filament\Clusters\Cluster;

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
        Filament::registerRenderHook(
            'panels::global-search.after',
            fn (): HtmlString => new HtmlString('
                <div class="flex items-center space-x-4">
                    <a href="' . \App\Filament\Clusters\Organisasi::getUrl() . '" class="px-3 py-2 rounded-lg flex items-center gap-1 ' . (Request::is('admin/organisasi*') ? 'text-primary-500 bg-gray-50' : '') .' ">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        Organisasi CSR
                    </a>
                    <!-- Tambahkan tautan resource lain jika perlu -->
                </div>
            ')
        );
    }
}
