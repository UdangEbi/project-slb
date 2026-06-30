<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\View::composer('layouts.kasir', function ($view) {
            $kategori = \App\Models\KategoriProduk::orderBy('nama_kategori')->get();
            $kategoriId = request()->kategori ?? ($kategori->first()->id_kategori ?? null);

            $view->with('kategori', $kategori);
            $view->with('kategoriId', $kategoriId);
        });
    }
}
