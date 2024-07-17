<?php

namespace App\Providers;

use App\Models\Admin\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        View::composer('theme.lte.aside', function ($view){
            $menus=Menu::getMenu(true);
            $view->with('menusComposer',$menus);
        });
        View::share('theme', 'lte');
    }
}
