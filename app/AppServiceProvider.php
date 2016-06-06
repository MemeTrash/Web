<?php

declare(strict_types=1);

namespace App;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        $this->register(MemeClient::class, function (Container $app) {
            return new Client($app->config->get('services.meme.generator'), $app->basePath('resources/img'), $app->basePath('public/result'));
        });

        $this->app->get('/', function () {
            return view('index');
        });

        $this->app->post('lol', function (Request $request) {
            $text = $request->get('text');

            if (!$text) {
                throw new BadRequestHttpException('No meme text provided!');
            }

            if (preg_match('/^[a-z0-9 .\-]+$/i', $text) !== 1) {
                throw new BadRequestHttpException('Invalid meme text provided!');
            }

            if (strlen($text) > 128) {
                throw new BadRequestHttpException('Meme text too long!');
            }

            $task = str_random(16);

            dispatch(new MemeJob($task, $text));

            return new JsonResponse([
                'success' => ['message' => 'Memes are to follow!'],
                'data'    => ['task' => $task],
            ], 202);
        });
    }
}
