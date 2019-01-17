# Laravel Subscribe

## Introduction

IMPORTANT: This is not a package for managing subscribers related to memberships and payed methods. It is used for managing user/subscriber relations just like
in YouTube for example, where the model can be subscribed by user.

This package is based on an idea and an architecture of the [Laravel Love](https://github.com/cybercog/laravel-love) package, although the functionality is different.

## Features

- Uses UUIDs instead of integers (your user model must use them as well!)
- Designed to work with Laravel Eloquent models.
- Using contracts to keep high customization capabilities.
- Using traits to get functionality out of the box.
- Most part of the the logic is handled by the `SubscribeableService`.
- Has Artisan command `gosubscribe:recount {model?}` to re-fetch subscribe counters.
- Subscribes for one model are mutually exclusive.
- Get Subscribeable models ordered by subscribe count.
- Events for `subscribe`, `unsubscribe` methods.
- Following PHP Standard Recommendations:
  - [PSR-1 (Basic Coding Standard)](http://www.php-fig.org/psr/psr-1/).
  - [PSR-2 (Coding Style Guide)](http://www.php-fig.org/psr/psr-2/).
  - [PSR-4 (Autoloading Standard)](http://www.php-fig.org/psr/psr-4/).
- Covered with unit tests.

## Installation

First, pull in the package through Composer.

```sh
$ composer require gorankrgovic/laravel-subscribe
```

#### Perform Database Migration

At last you need to publish and run database migrations.

```sh
$ php artisan migrate
```

If you want to make changes in migrations, publish them to your application first.

```sh
$ php artisan vendor:publish --provider="Gox\Laravel\Subscribe\Providers\SubscribeServiceProvider" --tag=migrations
```

## Usage

### Prepare Subscriber Model

Use `Gox\Contracts\Subscribe\Subscriber\Models\Subscriber` contract in model which will get subscribes
behavior and implement it or just use `Gox\Laravel\Subscribe\Subscriber\Models\Traits\Subscriber` trait. 

```php
use Gox\Contracts\Subscribe\Subscriber\Models\Subscriber as SubscriberContract;
use Gox\Laravel\Subscribe\Subscriber\Models\Traits\Subscriber;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements SubscriberContract
{
    use Subscriber;
}
```

### Prepare Subscribeable Model

Use `Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable` contract in model which will get subscribes
behavior and implement it or just use `Gox\Laravel\Subscribe\Subscribeable\Models\Traits\Subscribeable` trait. 

```php
use Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable as SubscribeableContract;
use Gox\Laravel\Subscribe\Subscribeable\Models\Traits\Subscribeable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements SubscribeableContract
{
    use Subscribeable;
}
```

### Available Methods

#### Subscribes

##### Subscribe model


```php
$user->subscribe($article);

$article->subscrubeBy(); // current user
$article->subscribeBy($user->id);
```

##### Remove subscribe mark from model

```php
$user->unsubscribe($article);

$article->unsubscribeBy(); // current user
$article->unsubscribeBy($user->id);
```

##### Get model subscribe count

```php
$article->subcribesCount;
```

##### Get model subscribes counter

```php
$article->subscribesCounter;
```

##### Get subscribes relation

```php
$article->subscribes();
```

##### Get iterable `Illuminate\Database\Eloquent\Collection` of existing model subscribes

```php
$article->subscribes;
```

##### Boolean check if user subscribed to model

```php
$user->hasSubscribed($article);

$article->subscribed; // current user
$article->isSubscribedBy(); // current user
$article->isSubscribedBy($user->id);
```

*Checks in eager loaded relations `subscribes` first.*

##### Get collection of users who subscribed to model

```php
$article->collectSubscribers();
```

##### Delete all subscribers for model

```php
$article->removeSubscribes();
```

### Scopes

##### Find all articles subscribed by user

```php
Article::whereSubscribedBy($user->id)
    ->with('subscribesCounter') // Allow eager load (optional)
    ->get();
```


##### Fetch Subscribeable models by subscribes count

```php
$sortedArticles = Article::orderBySubscribesCount()->get();
$sortedArticles = Article::orderBySubscribesCount('asc')->get();
```

*Uses `desc` as default order direction.*

### Events

On each subscribe added `\Gox\Laravel\Subscribe\Subscribeable\Events\SubscribeableWasSubscribed` event is fired.

On each subscribe removed `\Gox\Laravel\Subscribe\Subscribeable\Events\SubscribeableWasUnsubscribed` event is fired.

### Console Commands

##### Recount subscribers of all model types

```sh
$ gosubscribe:recount
```

##### Recount of concrete model type (using morph map alias)

```sh
$ gosubscribe:recount --model="article"
```

##### Recount of concrete model type (using fully qualified class name)

```sh
$ gosubscribe:recount --model="App\Models\Article"
```

## Extending

You can override core classes of package with your own implementations:

- `Gox\Laravel\Subscribe\Subscribe\Models\Subscribe`
- `Gox\Laravel\Subscribe\SubscribeCounter\Models\SubscribeCounter`
- `Gox\Laravel\Subscribe\Subscribeable\Services\SubscribeableService`

*Note: Don't forget that all custom models must implement original models interfaces.*

To make it you should use container [binding interfaces to implementations](https://laravel.com/docs/master/container#binding-interfaces-to-implementations) in your application service providers.

##### Use model class own implementation

```php
$this->app->bind(
    \Gox\Contracts\Subscribe\Subscribe\Models\Subscribe::class,
    \App\Models\CustomSubscribe::class
);
```

After that your `CustomSubscribe` class will be instantiable with helper method `app()`.

```php
$model = app(\Gox\Contracts\Subscribe\Subscribe\Models\Subscribe::class);
```

## Testing

Run the tests with:

```sh
$ vendor/bin/phpunit
```

## Security

If you discover any security related issues, please email me instead of using the issue tracker.

## License

- `Laravel Subscribe` package is open-sourced software licensed under the [MIT license](LICENSE) by Goran Krgovic.