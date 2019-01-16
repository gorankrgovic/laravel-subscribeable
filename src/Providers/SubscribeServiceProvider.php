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

namespace Gox\Laravel\Subscribe\Providers;

use Gox\Laravel\Subscribe\Console\Commands\RecountCommand;
use Gox\Contracts\Subscribe\Subscribe\Models\Subscribe as SubscribeContract;
use Gox\Contracts\Subscribe\SubscribeCounter\Models\SubscribeCounter as SubscribeCounterContract;
use Gox\Contracts\Subscribe\Subscribeable\Services\SubscribeableService as SubscribeableServiceContract;
use Gox\Laravel\Subscribe\Subscribe\Models\Subscribe;
use Gox\Laravel\Subscribe\Subscribe\Observers\SubscribeObserver;
use Gox\Laravel\Subscribe\Subscribeable\Services\SubscribeableService;
use Gox\Laravel\Subscribe\SubscribeCounter\Models\SubscribeCounter;
use Illuminate\Support\ServiceProvider;


/**
 * Class SubscribeServiceProvider
 * @package Gox\Laravel\Subscribe\Providers
 */
class SubscribeServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConsoleCommands();
        $this->registerObservers();
        $this->registerPublishes();
        $this->registerMigrations();
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerContracts();
    }

    /**
     * Register Love's models observers.
     *
     * @return void
     */
    protected function registerObservers()
    {
        $this->app->make(SubscribeContract::class)->observe(SubscribeObserver::class);
    }


    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerConsoleCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RecountCommand::class,
            ]);
        }
    }

    /**
     * Register classes in the container.
     *
     * @return void
     */
    protected function registerContracts()
    {
        $this->app->bind(SubscribeContract::class, Subscribe::class);
        $this->app->bind(SubscribeCounterContract::class, SubscribeCounter::class);
        $this->app->singleton(SubscribeableServiceContract::class, SubscribeableService::class);
    }

    /**
     * Setup the resource publishing groups
     *
     * @return void
     */
    protected function registerPublishes()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'migrations');
        }
    }

    /**
     * Register the Subscribe migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }






}