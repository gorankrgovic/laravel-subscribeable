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

namespace Gox\Contracts\Subscribe\Subscriber\Exceptions;

use RuntimeException;

/**
 * Class InvalidSubscriber
 * @package Gox\Contracts\Subscribe\Subscriber\Exceptions
 */
class InvalidSubscriber extends RuntimeException
{
    public static function notDefined()
    {
        return new static('Subscriber not defined.');
    }
}