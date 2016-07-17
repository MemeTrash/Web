<?php

declare(strict_types=1);

namespace App\Generators;

/**
 * This is the meme generator interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class GeneratorInterface
{
    /**
     * Generate a new image.
     *
     * @param string $text
     *
     * @throws \App\Generators\ExceptionInterface
     *
     * @return string
     */
    public function generate(string $text);
}
