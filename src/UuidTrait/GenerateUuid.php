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

namespace Gox\Laravel\Subscribe\UuidTrait;

use Ramsey\Uuid\Uuid;

trait GenerateUuid
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Create an UUID instead of autoincrement value
         */
        static::creating(function ($instance) {
            $instance->id = Uuid::uuid4()->toString();
        });
    }
}

