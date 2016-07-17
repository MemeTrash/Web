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
     * Start the process.
     *
     * @return $this
     */
    public function start()
    {
        $this->process->start();

        return $this;
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

        if (!$this->process->isSuccessful()) {
            throw new GenerationException($process->getOutput() ?: $process->getErrorOutput());
        }
    }
}
