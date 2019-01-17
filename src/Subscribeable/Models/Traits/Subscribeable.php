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

namespace Gox\Laravel\Subscribe\Subscribeable\Models\Traits;

use Gox\Contracts\Subscribe\Subscribe\Models\Subscribe as SubscribeContract;
use Gox\Contracts\Subscribe\SubscribeCounter\Models\SubscribeCounter as SubscribeCounterContract;
use Gox\Contracts\Subscribe\Subscribeable\Services\SubscribeableService as SubscribeableServiceContract;
use Gox\Laravel\Subscribe\Subscribeable\Observers\SubscribeableObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;


trait Subscribeable
{
    /**
     * Boot trait
     */
    public static function bootSubscribeable()
    {
        static::observe(SubscribeableObserver::class);
    }

    /**
     * @return mixed
     */
    public function subscribes()
    {
        return $this->morphMany(app(SubscribeContract::class), 'subscribeable');
    }

    /**
     * @return mixed
     */
    public function subscribesCounter()
    {
        return $this->morphOne(app(SubscribeCounterContract::class), 'subscribeable');
    }

    /**
     * @return mixed
     */
    public function collectSubscribers()
    {
        return app(SubscribeableServiceContract::class)->collectSubscribersOf($this);
    }

    /**
     * @return int
     */
    public function getSubscribesCountAttribute(): int
    {
        return $this->subscribesCounter ? $this->subscribesCounter->count : 0;
    }

    /**
     * @return bool
     */
    public function getSubscribedAttribute(): bool
    {
        return $this->isSubscribedBy();
    }

    /**
     * @param null $userId
     */
    public function subscribeBy($userId = null)
    {
        app(SubscribeableServiceContract::class)->addSubscribeTo($this, $userId);
    }

    /**
     * @param null $userId
     */
    public function unsubscribeBy($userId = null)
    {
        app(SubscribeableServiceContract::class)->removeSubscribeFrom($this, $userId);
    }


    /**
     * Remove subscribes from the model
     */
    public function removeSubscribes()
    {
        app(SubscribeableServiceContract::class)->removeModelSubscribes($this);
    }

    /**
     * @param null $userId
     * @return bool
     */
    public function isSubscribedBy($userId = null): bool
    {
        return app(SubscribeableServiceContract::class)->isSubscribed($this, $userId);
    }


    /**
     * Fetch records that are subscribed by a given user id.
     *
     * @param Builder $query
     * @param null $userId
     * @return Builder
     */
    public function scopeWhereSubscribedBy(Builder $query, $userId = null): Builder
    {
        return $this->applyScopeWhereSubscribedBy($query, $userId);
    }


    /**
     * Fetch records sorted by subscribes count.
     *
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    public function scopeOrderBySubscribesCount(Builder $query, string $direction = 'desc'): Builder
    {
        return $this->applyScopeOrderBySubscribesCount($query, $direction);
    }


    /**
     * @param Builder $query
     * @param $userId
     * @return Builder
     */
    private function applyScopeWhereSubscribedBy(Builder $query, $userId): Builder
    {
        $service = app(SubscribeableServiceContract::class);
        $userId = $service->getSubscriberUserId($userId);
        return $query->whereHas('subscribes', function (Builder $innerQuery) use ($userId) {
            $innerQuery->where('user_id', $userId);
        });
    }


    /**
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    private function applyScopeOrderBySubscribesCount(Builder $query, string $direction): Builder
    {
        $subscribeable = $query->getModel();
        return $query
            ->select($subscribeable->getTable() . '.*', 'subscribe_counters.count')
            ->leftJoin('subscribe_counters', function (JoinClause $join) use ($subscribeable) {
                $join
                    ->on('subscribe_counters.subscribeable_id', '=', "{$subscribeable->getTable()}.{$subscribeable->getKeyName()}")
                    ->where('subscribe_counters.subscribeable_type', '=', $subscribeable->getMorphClass());
            })
            ->orderBy('subscribe_counters.count', $direction);
    }






}