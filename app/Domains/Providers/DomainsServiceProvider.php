<?php

namespace App\Domains\Providers;

use App\Domains\Repositories\CurrenciesRepository;
use App\Domains\Repositories\ExchangesHistoricsRepository;
use App\Domains\Repositories\ExchangesRepository;
use App\Domains\Repositories\I18nRepository;
use App\Domains\Repositories\PermissionsRepository;
use App\Domains\Repositories\RolesRepository;
use App\Domains\Repositories\TranslationsRepository;
use Illuminate\Support\ServiceProvider;
use App\Domains\Repositories\UsersRepository;

class DomainsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //User Domain
        $this->app->singleton(UsersRepository::class, UsersRepository::class);

        //Role Domain
        $this->app->singleton(RolesRepository::class, RolesRepository::class);

        //Role Domain
        $this->app->singleton(PermissionsRepository::class, PermissionsRepository::class);

        //Currency Domain
        $this->app->singleton(CurrenciesRepository::class, CurrenciesRepository::class);

        //Exchange Domain
        $this->app->singleton(ExchangesRepository::class, ExchangesRepository::class);

        //ExchangeHistoric Domain
        $this->app->singleton(ExchangesHistoricsRepository::class, ExchangesHistoricsRepository::class);

        //Translation Domain
        $this->app->singleton(TranslationsRepository::class, TranslationsRepository::class);

        //Translations handler
        $this->app->singleton(I18nRepository::class, I18nRepository::class);
    }
}