<?php

declare(strict_types=1);

namespace App;

use App\Generators\CatGenerator;
use App\Generators\DogeGenerator;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

/**
 * This is the app service provider.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CatGenerator::class, function (Container $app) {
            return new CatGenerator($app->config->get('services.meme.cat'), $app->basePath('resources/img'), $app->basePath('public/result'));
        });

        $this->app->singleton(DogeGenerator::class, function (Container $app) {
            return new DogeGenerator($app->config->get('services.meme.doge'), $app->basePath('public/result'));
        });

        $this->app->get('/', function () {
            return view('index');
        });

        $this->app->post('lol', function (Request $request) {
            dispatch(new MemeJob(str_random(16), (string) $request->get('text'), (bool) random_int(0, 1)));

            return new JsonResponse([
                'success' => ['message' => 'Memes are to follow!'],
                'data'    => ['task' => $task],
            ], 202);
        });
    }
}
