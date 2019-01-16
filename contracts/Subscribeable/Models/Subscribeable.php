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

namespace Gox\Contracts\Subscribe\Subscribeable\Models;

/**
 * Interface Subscribeable
 * @package Gox\Contracts\Subscribe\Subscribeable\Models
 */
interface Subscribeable
{
    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass();


    /**
     * Collection of the subscribes on this record.
     *
     * @return mixed
     */
    public function subscribes();


    /**
     * Counter is a record that stores the total subscribes for the morphed record.
     *
     * @return mixed
     */
    public function subscribesCounter();


    /**
     * Fetch users who subscribed to the entity.
     *
     * @todo Do we need to rely on the Laravel Collections here?
     * @return \Illuminate\Support\Collection
     */
    public function collectSubscribers();


    /**
     * Add a subscribe for model by the given user.
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @return void
     *
     * @throws \Gox\Contracts\Subscribe\Subscriber\Exceptions\InvalidSubscriber
     */
    public function subscribeBy($userId = null);



    /**
     * Remove a susbcribe for model by the given user.
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @return void
     *
     * @throws \Gox\Contracts\Subscribe\Subscriber\Exceptions\InvalidSubscriber
     */
    public function unsubscribeBy($userId = null);



    /**
     * Delete subscribes related to the current record.
     *
     * @return void
     */
    public function removeSubscribes();


    /**
     * Has the user already subscribed subscribeable model.
     *
     * @param null|string|int $userId
     * @return bool
     */
    public function isSubscribedBy($userId = null): bool;



}