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

namespace Gox\Laravel\Subscribe\Subscriber\Models\Traits;

use Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable as SubscribeableContract;
use Gox\Contracts\Subscribe\Subscribeable\Services\SubscribeableService as SubscribeableServiceContract;

/**
 * Trait Subscriber
 * @package Gox\Laravel\Subscribe\Subscriber\Models\Traits
 */
trait Subscriber
{

    /**
     * @param SubscribeableContract $subscribeable
     */
    public function subscribe(SubscribeableContract $subscribeable)
    {
        app(SubscribeableServiceContract::class)->addSubscribeTo($subscribeable, $this);
    }

    /**
     * @param SubscribeableContract $subscribeable
     */
    public function unsubscribe(SubscribeableContract $subscribeable)
    {
        app(SubscribeableServiceContract::class)->removeSubscribeFrom($subscribeable, $this);
    }


    /**
     * @param SubscribeableContract $subscribeable
     * @return bool
     */
    public function hasSubscribed(SubscribeableContract $subscribeable): bool
    {
        return app(SubscribeableServiceContract::class)->isSubscribed($subscribeable, $this);
    }

}