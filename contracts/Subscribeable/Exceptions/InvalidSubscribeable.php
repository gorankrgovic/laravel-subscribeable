<?php
/*
 * This file is part of Laravel Subscribe.
 *
 * (c) Goran Krgovic <gorankrgovic1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Gox\Contracts\Subscribe\Subscribeable\Exceptions;

use RuntimeException;


/**
 * Class InvalidSubscribeable
 * @package Gox\Contracts\Subscribe\Subscribeable\Exceptions
 */
class InvalidSubscribeable extends RuntimeException
{
    public static function notExists(string $type)
    {
        return new static("{$type} class or morph map not found");
    }

    public static function notImplementInterface(string $type)
    {
        return new static ("[{$type}] must implement `\Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable` contract");
    }

}
