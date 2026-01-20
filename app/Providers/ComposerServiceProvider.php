<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Http\View\Composers\CartComposer;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.header', CartComposer::class);
    }
}

