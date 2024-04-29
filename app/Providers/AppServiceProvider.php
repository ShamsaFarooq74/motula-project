<?php

namespace App\Providers;

use App\Http\Models\ProductCategory;
use App\Http\Models\Notification;
use App\Http\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Http\Models\Config;
use App\Http\Models\Setting;

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
        View::composer('*', function ($view) {
            $view->with('config', Config::all());
            $view->with('setting', Setting::all());
        });
    }
}

