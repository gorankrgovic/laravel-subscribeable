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

namespace Gox\Contracts\Subscribe\Subscribeable\Services;

use Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable as SubscribeableContract;


interface SubscribeableService
{

    /**
     * Add a subscribe to subscribeable model by user.
     *
     * @param \Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable $subscribeable
     * @param string $userId
     * @return void
     *
     * @throws \Gox\Contracts\Subscribe\Subscribe\Exceptions\InvalidSubscribeType
     * @throws \Gox\Contracts\Subscribe\Subscriber\Exceptions\InvalidSubscriber
     */
    public function addSubscribeTo(SubscribeableContract $subscribeable, $userId);

    /**
     * @param SubscribeableContract $subscribeable
     * @param $type
     * @param $userId
     * @return mixed
     */
    public function removeSubscribeFrom(SubscribeableContract $subscribeable, $userId);

    /**
     * @param SubscribeableContract $subscribeable
     * @param $type
     * @param $userId
     * @return mixed
     */
    public function isSubscribed(SubscribeableContract $subscribeable, $userId): bool;

    /**
     * @param SubscribeableContract $subscribeable
     * @return mixed
     */
    public function decrementSubscribesCount(SubscribeableContract $subscribeable);

    /**
     * @param SubscribeableContract $subscribeable
     * @return mixed
     */
    public function incrementSubscribesCount(SubscribeableContract $subscribeable);

    /**
     * @param $subscribeableType
     * @param null $type
     * @return mixed
     */
    public function removeSubscribeCountersOfType($subscribeableType);

    /**
     * @param SubscribeableContract $subscribeable
     * @param $type
     * @return mixed
     */
    public function removeModelSubscribes(SubscribeableContract $subscribeable);

    /**
     * @param SubscribeableContract $subscribeable
     * @return mixed
     */
    public function collectSubscribersOf(SubscribeableContract $subscribeable);

    /**
     * @param $subscribeableTypes
     * @param $likeType
     * @return array
     */
    public function fetchSubscribesCounters($subscribeableType): array;
}