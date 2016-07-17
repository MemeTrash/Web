<?php

declare(strict_types=1);

namespace App\Generators;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * This is the multi generator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class MultiGenerator implements GeneratorInterface
{
    /**
     * The generator to wrap.
     *
     * @var \App\Generators\GeneratorInterface
     */
    protected $generator;

    /**
     * The number of generations.
     *
     * @var int
     */
    protected $times;

    /**
     * Create a new multi generator instance.
     *
     * @param \App\Generators\GeneratorInterface $generator
     * @param int                                $times
     *
     * @return void
     */
    public function __construct(GeneratorInterface $generator, int $times = 3)
    {
        $this->generator = $generator;
        $this->times = $times;
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
        app('Psr\Log\LoggerInterface')->debug('Entering multi gen main');

        return new Promise(function () use ($text) {
            app('Psr\Log\LoggerInterface')->debug('Entering multi gen wait 1');

            $promises = [];

            for ($i = 0; $i < $this->times; $i++) {
                $promises[] = $this->generator->generate($text);
            }

            return new Promise(function () use ($promises) {
                app('Psr\Log\LoggerInterface')->debug('Entering multi wait 2');

                $result = [];

                while ($promises) {
                    app('Psr\Log\LoggerInterface')->debug('Entering multi gen loop');
                    foreach ($promises as $index => $promise) {
                        $new = $promise->wait(false);

                        if ($new instanceof PromiseInterface) {
                            $promises[$index] = $new;
                        } else {
                            unset($promises[$index]);
                            $result += $new;
                        }
                    }
                }

                return $result;
            });
        });
    }
}
