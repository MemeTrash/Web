<?php

declare(strict_types=1);

namespace App\Generators;

use Symfony\Component\Process\Process;

/**
 * This is the process runner class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ProcessRunner
{
    /**
     * The symfony process instance.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * Create a new process runner instance.
     *
     * @param Symfony\Component\Process\Process $process
     *
     * @return void
     */
    public function __construct(string $command)
    {
        $this->process = new Process($command);
    }

    /**
     * Run the process compeltely.
     *
     * @param string $text
     *
     * @throws \App\Generators\ExceptionInterface
     *
     * @return void
     */
    public function run()
    {
        $this->start();

        $this->wait();
    }

    /**
     * Start the process.
     *
     * @return void
     */
    public function start()
    {
        $this->process->start();
    }

    /**
     * Wait for completion.
     *
     * @throws \App\Generators\ExceptionInterface
     *
     * @return void
     */
    public function wait()
    {
        $this->process->wait();

        if (!$process->isSuccessful()) {
            throw new GenerationException($process->getOutput() ?: $process->getErrorOutput());
        }
    }
}
