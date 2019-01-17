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


namespace Gox\Laravel\Subscribe\Subscribeable\Services;


use Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable as SubscribeableContract;
use Gox\Contracts\Subscribe\Subscribeable\Services\SubscribeableService as SubscribeableServiceContract;
use Gox\Contracts\Subscribe\Subscribe\Models\Subscribe as SubscribeContract;
use Gox\Contracts\Subscribe\SubscribeCounter\Models\SubscribeCounter as SubscribeCounterContract;
use Gox\Contracts\Subscribe\Subscriber\Exceptions\InvalidSubscriber;
use Gox\Contracts\Subscribe\Subscriber\Models\Subscriber as SubscriberContract;
use Illuminate\Support\Facades\DB;


class SubscribeableService implements SubscribeableServiceContract
{

    /**
     * Add subscribe to
     *
     * @param SubscribeableContract $subscribeable
     * @param string $userId
     */
    public function addSubscribeTo(SubscribeableContract $subscribeable, $userId)
    {
        $userId = $this->getSubscriberUserId($userId);

        $subscribe = $subscribeable->subscribes()->where([
            'user_id' => $userId,
        ])->first();


        if ( !$subscribe ) {
            $subscribeable->subscribes()->create([
                'user_id' => $userId
            ]);

            return;
        }

        $subscribe->delete();

        $subscribeable->subscribes()->create([
           'user_id' => $userId
        ]);
    }

    /**
     * Remove a subscribe from model by user
     *
     * @param SubscribeableContract $subscribeable
     * @param $userId
     * @return mixed|void
     */
    public function removeSubscribeFrom(SubscribeableContract $subscribeable, $userId)
    {
        $subscribe = $subscribeable->subscribes()->where([
            'user_id' => $this->getSubscriberUserId($userId)
        ])->first();

        if ( !$subscribe )
        {
            return;
        }

        $subscribe->delete();
    }


    /**
     * @param SubscribeableContract $subscribeable
     * @param $userId
     * @return mixed
     */
    public function isSubscribed(SubscribeableContract $subscribeable, $userId): bool
    {
        if ($userId instanceof SubscribeContract) {
            $userId = $userId->getKey();
        }

        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if ( !$userId )
        {
            return false;
        }


        return $subscribeable->subscribes()->where([
            'user_id' => $userId
        ])->exists();
    }


    /**
     * @param SubscribeableContract $subscribeable
     * @return mixed|void
     */
    public function incrementSubscribesCount(SubscribeableContract $subscribeable)
    {
        $counter = $subscribeable->subscribesCounter()->first();

        if ( !$counter ) {
            $counter = $subscribeable->subscribesCounter()->create([
                'count' => 0,
            ]);
        }

        $counter->increment('count');
    }

    /**
     * @param SubscribeableContract $subscribeable
     * @return mixed|void
     */
    public function decrementSubscribesCount(SubscribeableContract $subscribeable)
    {
        $counter = $subscribeable->subscribesCounter()->first();

        if ( !$counter ) {
           return;
        }

        $counter->decrement('count');
    }


    /**
     * @param $subscribeableType
     * @return mixed|void
     */
    public function removeSubscribeCountersOfType($subscribeableType)
    {
        if (class_exists($subscribeableType)) {

            $subscribeable = new $subscribeableType;
            $subscribeableType = $subscribeable->getMorphClass();
        }

        /** @var \Illuminate\Database\Eloquent\Builder $counters */
        $counters = app(SubscribeCounterContract::class)->where('subscribeable_type', $subscribeableType);
        $counters->delete();
    }


    /**
     * @param SubscribeableContract $subscribeable
     * @return mixed|void
     */
    public function removeModelSubscribes(SubscribeableContract $subscribeable)
    {
        app(SubscribeContract::class)->where([
            'subscribeable_id' => $subscribeable->getKey(),
            'subscribeable_type' => $subscribeable->getMorphClass()
        ])->delete();

        app( SubscribeCounterContract::class)->where([
            'subscribeable_id' => $subscribeable->getKey(),
            'subscribeable_type' => $subscribeable->getMorphClass()
        ])->delete();
    }


    /**
     * @param SubscribeableContract $subscribeable
     * @return mixed
     */
    public function collectSubscribersOf(SubscribeableContract $subscribeable)
    {
        $userModel = $this->resolveUserModel();

        $subscribersIds = $subscribeable->subscribes->pluck('user_id');

        return $userModel::whereKey($subscribersIds)->get();
    }


    /**
     * @param $subscribeableType
     * @return array
     */
    public function fetchSubscribesCounters($subscribeableType): array
    {
        /** @var \Illuminate\Database\Eloquent\Builder $likesCount */

        $subscribesCount = app(SubscribeContract::class)
            ->select([
                DB::raw('COUNT(*) AS count'),
                'subscribeable_type',
                'subscribeable_id'
            ])
            ->where('subscribeable_type', $subscribeableType);

        $subscribesCount->groupBy('subscribeable_id');

        return $subscribesCount->get()->toArray();
    }


    /**
     * @param $userId
     * @return string
     */
    public function getSubscriberUserId($userId): string
    {
        if ($userId instanceof SubscriberContract) {
            return $userId->getKey();
        }
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }
        if (!$userId) {
            throw InvalidSubscriber::notDefined();
        }
        return $userId;
    }


    /**
     * Fetch the primary ID of the currently logged in user.
     *
     * @return null|string
     */
    protected function loggedInUserId()
    {
        return auth()->id();
    }


    /**
     * Retrieve User's model class name.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    private function resolveUserModel()
    {
        return config('auth.providers.users.model');
    }

}