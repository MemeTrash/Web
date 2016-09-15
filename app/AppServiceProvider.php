<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\ServiceProvider;

/**
 * This is the app service provider.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->get('/', 'App\Controllers\MainController@show');
    }
}
