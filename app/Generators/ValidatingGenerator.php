<?php

declare(strict_types=1);

namespace App\Generators;

use GuzzleHttp\Promise\Promise;

/**
 * This is the validating generator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ValidatingGenerator implements GeneratorInterface
{
    /**
     * The generator to wrap.
     *
     * @var \App\Generators\GeneratorInterface
     */
    protected $generator;

    /**
     * Create a new validating generator instance.
     *
     * @param \App\Generators\GeneratorInterface $generator
     *
     * @return void
     */
    public function __construct(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Generate a new image.
     *
     * @param string $text
     *
     * @throws \App\Generators\ExceptionInterface
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function generate(string $text)
    {
        app('Psr\Log\LoggerInterface')->debug('Entering val gen main');

        return new Promise(function () use ($text) {
            app('Psr\Log\LoggerInterface')->debug('Entering val gen wait 1');

            if (!$text) {
                throw new ValidationException('No meme text provided!');
            }

            if (preg_match('/^[a-z0-9 .\-]+$/i', $text) !== 1) {
                throw new ValidationException('Invalid meme text provided!');
            }

            if (strlen($text) > 128) {
                throw new ValidationException('Meme text too long!');
            }

            return new Promise(function () {
                app('Psr\Log\LoggerInterface')->debug('Entering val gen wait 2');

                return $this->generator->generate($text);
            })
        });
    }
}
