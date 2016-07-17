<?php

declare(strict_types=1);

namespace App\Controllers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller;

/**
 * This is the main controller class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class MainController extends Controller
{
    /**
     * Show the homepage.
     *
     * @param \Illuminate\Contracts\View\Factory $view
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Factory $view)
    {
        return new Response($view->make('index'));
    }

    /**
     * Generate the memes.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     * @param \Illuminate\Http\Request                  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Container $container, Request $request)
    {
        $inner = $container->make(random_int(0, 1) ? CatGenerator::class : DogeGenerator::class);

        $generator = new ValidatingGenerator(new MultiGenerator($inner));

        $images = $generator->generate((string) $request->get('text'))->wait();

        return new JsonResponse([
            'success' => ['message' => 'Here are your memes!'],
            'data'    => ['images' => $images],
        ]);
    }
}
