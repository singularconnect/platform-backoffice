<?php

namespace App\Applications\Api\Providers;

use App\Core\Providers\AbstractApplicationServiceProvider;
use Illuminate\Contracts\Routing\Registrar as Router;

class ApiServiceProvider extends AbstractApplicationServiceProvider
{
    /**
     * Register application routes
     *
     * @param Router $router
     * @return void
     */
    function registerRoutes(Router $router) {
        $attributes = [
            'prefix' => 'api',
            'namespace' => 'App\Applications\Api\Http\Controllers',
            'middleware' => ['api']
        ];

        $router->group($attributes, function ($router) {
            require $this->getApplicationDir() . '/Http/routes.php';
        });
    }

    /**
     * Get dir of application
     *
     * @return string
     */
    protected function getApplicationDir() {
        return app_path('Applications/Api');
    }
}