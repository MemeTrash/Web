<?php

declare(strict_types=1);

namespace App\Generators;

use RuntimeException;
use Symfony\Component\Process\Process;

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
     * The daemon doge generator.
     *
     * @var \Symfony\Component\Process\Process|null
     */
    protected $daemon;

    /**
     * The daemon uri.
     *
     * @var string|null
     */
    protected $uri;

    /**
     * Create a new doge meme generator instance.
     *
     * @param string $generator
     * @param string $output
     *
     * @return void
     */
    public function __construct(string $generator, string $output, bool $daemon = false)
    {
        $this->generator = $generator;
        $this->output = $output;

        if ($daemon) {
            $this->daemon = $this->start();
        }
    }

    /**
     * Destroy the current doge meme generator instance.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->daemon) {
            $this->stop();
        }
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

        foreach ($this->daemon as $type => $data) {
            if (Process::OUT === $type) {
                $this->uri = trim($data);
            } else {
                throw new RuntimeException($data);
            }

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
     * @throws \App\Generators\ExceptionInterface
     *
     * @return string
     */
    public function generate(string $text)
    {
        $name = str_random(16);

        if ($this->daemon) {
            $command = "python {$this->generator}/run.py --with-deamon \"{$this->uri}\" \"{$text}\" \"{$this->output}/{$name}.jpg\" 6";
        } else {
            $command = "python {$this->generator}/run.py \"{$text}\" \"{$this->output}/{$name}.jpg\" \"{$this->generator}/resources\" 6";
        }

        app('Psr\Log\LoggerInterface')->debug($command);

        $process = new Process($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new GenerationException($process->getOutput() ?: $process->getErrorOutput());
        }

        return $name;
    }
}
