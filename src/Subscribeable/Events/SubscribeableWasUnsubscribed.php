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

namespace Gox\Laravel\Subscribe\Subscribeable\Events;

use Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable as SubscribeableContract;

class SubscribeableWasUnsubscribed
{

    /**
     * The subscribed model
     *
     * @var SubscribeableContract
     */
    public $subscribeable;


    /**
     * User id who subscribed to model
     *
     * @var string
     */
    public $subscriberId;


    /**
     * Create a new event instance
     *
     * SubscribeableWasSubscribed constructor.
     * @param \Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable $subscribeable
     * @param string $subscriberId
     * @return void
     */
    public function __construct(SubscribeableContract $subscribeable, $subscriberId)
    {
        $this->subscriberId = $subscriberId;
        $this->subscribeable = $subscribeable;
    }
}