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

namespace Gox\Contracts\Subscribe\Subscribe\Models;


/**
 * Interface Subscribe
 *
 * @package Gox\Contracts\Subscribe\Subscribe\Models
 */
interface Subscribe
{
    /**
     * Subscribeable model relation.
     *
     * @return mixed
     */
    public function subscribeable();
}