<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
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
        if ($this->app->runningInConsole() || $this->app->runningUnitTests()) {
            return;
        }
        $menuPath = base_path('resources/assets/menu/menu.json');
        if (file_exists($menuPath)) {
            $menu = json_decode(file_get_contents($menuPath), true);

            View::share('menu', $menu);
        }
    }
}
