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

namespace Gox\Contracts\Subscribe\SubscribeCounter\Models;

/**
 * Interface SubscribeCounter
 * @package Gox\Contracts\Subscribe\SubscribeCounter\Models
 */
interface SubscribeCounter
{
    /**
     * Subscribeable model relation
     *
     * @return mixed
     */
    public function subscribeable();
}