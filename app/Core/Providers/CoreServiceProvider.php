<?php
namespace App\Core\Providers;
use Illuminate\Support\ServiceProvider;
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(resource_path('views/core'), 'core');
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}