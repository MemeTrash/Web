<?php

declare(strict_types=1);

namespace App\Generations;

use InvalidArgumentException;

/**
 * This is the validation exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ValidationException extends InvalidArgumentException implements ExceptionInterface
{
    //
}
