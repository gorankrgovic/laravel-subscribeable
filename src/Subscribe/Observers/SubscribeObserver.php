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

namespace Gox\Laravel\Subscribe\Subscribe\Observers;

use Gox\Contracts\Subscribe\Subscribe\Models\Subscribe as SubscribeContract;
use Gox\Contracts\Subscribe\Subscribeable\Services\SubscribeableService as SubscribeableServiceContract;
use Gox\Laravel\Subscribe\Subscribeable\Events\SubscribeableWasSubscribed;
use Gox\Laravel\Subscribe\Subscribeable\Events\SubscribeableWasUnsubscribed;

/**
 * Class SubscribeObserver
 * @package Gox\Laravel\Subscribe\Subscribe\Observers
 */
class SubscribeObserver
{

    /**
     * Handle the created event for the model
     *
     * @param SubscribeContract $subscribe
     * @return void
     */
    public function created(SubscribeContract $subscribe)
    {
        event(new SubscribeableWasSubscribed($subscribe->subscribeable, $subscribe->user_id));
        app(SubscribeableServiceContract::class)->incrementSubscribesCount($subscribe->subscribeable);
    }

    /**
     * Handle the deleted event for the model
     *
     * @param SubscribeContract $subscribe
     * @return void
     */
    public function deleted(SubscribeContract $subscribe)
    {
        event(new SubscribeableWasUnsubscribed($subscribe->subscribeable, $subscribe->user_id));
        app(SubscribeableServiceContract::class)->decrementSubscribesCount($subscribe->subscribeable);
    }

}

