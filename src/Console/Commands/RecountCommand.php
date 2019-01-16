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

namespace Gox\Laravel\Subscribe\Console\Commands;

use Gox\Contracts\Subscribe\Subscribeable\Exceptions\InvalidSubscribeable;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Events\Dispatcher;
use Gox\Contracts\Subscribe\Subscribeable\Services\SubscribeableService as SubscribeableServiceContract;
use Gox\Contracts\Subscribe\Subscribe\Models\Subscribe as SubscribeContract;
use Gox\Contracts\Subscribe\SubscribeCounter\Models\SubscribeCounter as SubscribeCounterContract;
use Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable as SubscribeableContract;
use Illuminate\Support\Facades\DB;


class RecountCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'gosubscribe:recount {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recount subscribes of the subscribeable models';


    /**
     * Subscribeable service.
     *
     * @var \Gox\Contracts\Subscribe\Subscribeable\Services\SubscribeableService
     */
    protected $service;


    /**
     * Execute the console command.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     *
     * @throws \Gox\Contracts\Subscribe\Subscribeable\Exceptions\InvalidSubscribeable
     */
    public function handle(Dispatcher $events)
    {
        $model = $this->argument('model');
        $this->likeType = $this->argument('type');
        $this->service = app(SubscribeableServiceContract::class);
        if (empty($model)) {
            $this->recountSubscribesOfAllModelTypes();
        } else {
            $this->recountSubscribesOfModelType($model);
        }
    }


    /**
     * Recount subscribes of all model types.
     *
     * @return void
     */
    protected function recountSubscribesOfAllModelTypes()
    {
        $sTypes = app(SubscribeContract::class)->groupBy('subscribeable_type')->get();
        foreach ($sTypes as $subscribe) {
            $this->recountSubscribesOfModelType($subscribe->subscribeable_type);
        }
    }


    /**
     * Recount subscribes of model type.
     *
     * @param string $modelType
     * @return void
     */
    protected function recountSubscribesOfModelType(string $modelType)
    {
        $modelType = $this->normalizeModelType($modelType);
        $counters = $this->service->fetchSubscribesCounters($modelType);
        $this->service->removeSubscribeCountersOfType($modelType);
        $sCounterTable = app(SubscribeCounterContract::class)->getTable();
        DB::table($sCounterTable)->insert($counters);
        $this->info('All [' . $modelType . '] records subscribes has been recounted.');
    }


    /**
     * Normalize subscribeable model type.
     *
     * @param string $modelType
     * @return string
     */
    protected function normalizeModelType(string $modelType): string
    {
        $model = $this->newModelFromType($modelType);
        $modelType = $model->getMorphClass();
        if (!$model instanceof SubscribeableContract) {
            throw InvalidSubscribeable::notImplementInterface($modelType);
        }
        return $modelType;
    }



    /**
     * Instantiate model from type or morph map value.
     *
     * @param string $modelType
     * @return mixed
     */
    private function newModelFromType(string $modelType)
    {
        if (class_exists($modelType)) {
            return new $modelType;
        }
        $morphMap = Relation::morphMap();
        if (!isset($morphMap[$modelType])) {
            throw InvalidSubscribeable::notExists($modelType);
        }
        $modelClass = $morphMap[$modelType];
        return new $modelClass;
    }




}