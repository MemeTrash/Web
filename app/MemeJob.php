<?php

declare(strict_types=1);

namespace App;

use App\Generators\CatGenerator;
use App\Generators\DogeGenerator;
use App\Generators\MultiGenerator;
use App\Generators\ValidatingGenerator;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher;

/**
 * This is the meme job.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class MemeJob implements ShouldQueue
{
    /**
     * The pusher channel name.
     *
     * @var string
     */
    const CHANNEL = 'lol';

    /**
     * The task id.
     *
     * @var string
     */
    protected $task;

    /**
     * The job text.
     *
     * @var string
     */
    protected $text;

    /**
     * Is the job doge?
     *
     * @var bool
     */
    protected $doge;

    /**
     * Create a new meme job instance.
     *
     * @param string $task
     * @param string $text
     * @param bool   $doge
     *
     * @return void
     */
    public function __construct(string $task, string $text, bool $doge)
    {
        $this->task = $task;
        $this->text = $text;
        $this->doge = $doge;
    }

    /**
     * Handle the meme job.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     *
     * @return void
     */
    public function handle(Container $container)
    {
        $pusher = $container->make(Pusher::class);
        $inner = $container->make($this->doge ? CatGenerator::class : DogeGenerator::class);
        $generator = new ValidatingGenerator(new MultiGenerator($inner));

        $images = $generator->generate($this->text);

        $pusher->trigger($this->task, self::CHANNEL, ['ids' => $images]);
    }
}
