<?php

declare(strict_types=1);

namespace App\Generators;

/**
 * This is the meme generator interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
interface GeneratorInterface
{
    /**
     * Generate a new image.
     *
     * @param string $text
     *
     * @throws \App\Generators\ExceptionInterface
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function generate(string $text);
}
