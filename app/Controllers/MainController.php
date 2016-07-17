<?php

declare(strict_types=1);

namespace App\Controllers;

use App\MemeJob;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
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
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request) {
    {
        dispatch(new MemeJob(str_random(16), (string) $request->get('text'), (bool) random_int(0, 1)));

        return new JsonResponse([
            'success' => ['message' => 'Memes are to follow!'],
            'data'    => ['task' => $task],
        ], 202);
    }
}
