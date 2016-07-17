<?php

declare(strict_types=1);

namespace App\Generators;

use GuzzleHttp\Promise\Promise;

/**
 * This is the cat meme generator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CatGenerator implements GeneratorInterface
{
    /**
     * The generator path.
     *
     * @var string
     */
    protected $generator;

    /**
     * The resources path.
     *
     * @var string
     */
    protected $resources;

    /**
     * The output path.
     *
     * @var string
     */
    protected $output;

    /**
     * Create a new cat meme generator instance.
     *
     * @param string $generator
     * @param string $resources
     * @param string $output
     *
     * @return void
     */
    public function __construct(string $generator, string $resources, string $output)
    {
        $this->generator = $generator;
        $this->resources = $resources;
        $this->output = $output;
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
        app('Psr\Log\LoggerInterface')->debug('Entering cat gen main');

        $name = str_random(16);

        return (new Promise(function () use ($text, $name) {
            app('Psr\Log\LoggerInterface')->debug('Entering cat gen wait');

            $image = random_int(1, 70);

            $command = "python {$this->generator}/run.py \"{$this->resources}/{$image}.jpg\" \"{$this->output}/{$name}.jpg\" \"{$this->generator}/resources\" \"{$text}\"";

            return (new ProcessRunner($command))->start();
        }))->then(function (Runner $runner) use ($name) {
            app('Psr\Log\LoggerInterface')->debug('Entering cat gen then');

            $runner->wait();

            return [$name];
        });
    }
}
