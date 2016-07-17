<?php

declare(strict_types=1);

namespace App\Generators;

use GuzzleHttp\Promise\Promise;

/**
 * This is the doge meme generator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DogeGenerator implements GeneratorInterface
{
    /**
     * The generator path.
     *
     * @var string
     */
    protected $generator;

    /**
     * The output path.
     *
     * @var string
     */
    protected $output;

    /**
     * Create a new doge meme generator instance.
     *
     * @param string $generator
     * @param string $output
     *
     * @return void
     */
    public function __construct(string $generator, string $output)
    {
        $this->generator = $generator;
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
        \Illuminate\Support\Facades\Log::debug('Entering cat doge main');

        $name = str_random(16);

        return (new Promise(function () use ($text, $name) {
            \Illuminate\Support\Facades\Log::debug('Entering doge gen wait');

            $command = "python {$this->generator}/run.py \"{$text}\" \"{$this->output}/{$name}.jpg\" \"{$this->generator}/resources\" 6";

            return (new ProcessRunner($command))->start();
        }))->then(function (Runner $runner) use ($name) {
            \Illuminate\Support\Facades\Log::debug('Entering doge gen then');

            $runner->wait();

            return [$name];
        });
    }
}
