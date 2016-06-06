<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Process\Process;

/**
 * This is the meme client.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class MemeClient
{
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
     * Create a new client instance.
     *
     * @param string $generator
     * @param string $resources
     * @param string $output
     *
     * @return void
     */
    public function __construct($generator, $resources, $output)
    {
        $this->generator = $generator;
        $this->resources = $resources;
        $this->output = $output;
    }

    /**
     * Start a new file conversion.
     *
     * @param int    $image
     * @param string $text
     *
     * @return string
     */
    public function generate(int $image, string $text)
    {
        $name = str_random(16);

        $process = new Process("{$this->generator} \"{$this->resources}\{$image}\" \"{$this->output}\{$name}\" \"{$text}\"");

        $process->run();

        return $name;
    }
}
