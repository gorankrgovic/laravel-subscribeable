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

namespace Gox\Contracts\Subscribe\Subscriber\Models;


use Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable;


interface Subscriber
{
    /**
     * @param Subscribeable $subscribeable
     * @return mixed
     */
    public function subscribe(Subscribeable $subscribeable);

    /**
     * @param Subscribeable $subscribeable
     * @return mixed
     */
    public function unsubscribe(Subscribeable $subscribeable);

    /**
     * @param Subscribeable $subscribeable
     * @return bool
     */
    public function hasSubscribed(Subscribeable $subscribeable): bool;

}