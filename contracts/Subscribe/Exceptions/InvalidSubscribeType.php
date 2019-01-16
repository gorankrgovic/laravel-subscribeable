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

namespace Gox\Contracts\Subscribe\Subscribe\Exceptions;

use RuntimeException;

/**
 * Class InvalidSubscribeType
 * @package Gox\Contracts\Subscribe\Subscribe\Exceptions
 */
class InvalidSubscribeType extends RuntimeException
{
    public static function notExists(string $type )
    {
        return new static("Subscribe type `{$type}` not exists`");
    }
}
