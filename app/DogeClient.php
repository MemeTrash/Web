<?php

declare(strict_types=1);

namespace App;

use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * This is the meme client.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DogeClient
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
     * The daemon doge generator.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected $daemon;

    /**
     * The daemon uri.
     *
     * @var string|null
     */
    protected $uri;

    /**
     * Create a new client instance.
     *
     * @param string $generator
     * @param string $output
     *
     * @return void
     */
    public function __construct($generator, $output)
    {
        $this->generator = $generator;
        $this->output = $output;
        $this->daemon = $this->start();

        app('Psr\Log\LoggerInterface')->debug('READY');
    }

    /**
     * Destroy the current client instance.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->stop();
    }

    /**
     * Start the daemon generator.
     *
     * @return void
     */
    protected function start()
    {
        $command = "python {$this->generator}/run.py --daemon-start \"{$this->generator}/resources\"";

        app('Psr\Log\LoggerInterface')->debug($command);

        $this->daemon = new Process($command);

        $this->daemon->start();

        app('Psr\Log\LoggerInterface')->debug('STARTED');

        foreach ($this->daemon as $type => $data) {
            app('Psr\Log\LoggerInterface')->debug('ENTERED LOOP');

            if (Process::OUT === $type) {
                $this->uri = trim($data);
            } else {
                throw new RuntimeException($data);
            }

            app('Psr\Log\LoggerInterface')->debug('BREAKING');

            break; // only read off the first line
        }
    }

    /**
     * Stop the daemon generator.
     *
     * @return void
     */
    protected function stop()
    {
        $this->daemon->stop();
    }

    /**
     * Generate a new image.
     *
     * @param string $text
     *
     * @throws \App\GenerationException
     *
     * @return string
     */
    public function generate(string $text)
    {
        $name = str_random(16);

        $command = "python {$this->generator}/run.py --with-deamon \"{$this->uri}\" \"{$text}\" \"{$this->output}/{$name}.jpg\" 5";

        app('Psr\Log\LoggerInterface')->debug($command);

        $process = new Process($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new GenerationException($process->getOutput() ?: $process->getErrorOutput());
        }

        return $name;
    }
}
