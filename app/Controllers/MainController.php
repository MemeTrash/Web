<?php

declare(strict_types=1);

namespace App\Controllers;

use Illuminate\Contracts\View\Factory;
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
        $response = new Response($view->make('index'));

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
