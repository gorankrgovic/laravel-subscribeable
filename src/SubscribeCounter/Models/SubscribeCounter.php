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

namespace Gox\Laravel\Subscribe\SubscribeCounter\Models;


use Gox\Contracts\Subscribe\SubscribeCounter\Models\SubscribeCounter as SubscribeCounterContract;
use Gox\Laravel\Subscribe\UuidTrait\GenerateUuid;
use Illuminate\Database\Eloquent\Model;


class SubscribeCounter extends Model implements SubscribeCounterContract
{

    use GenerateUuid;

    /**
     * @var string
     */
    protected $table = 'subscribe_counters';


    /**
     * Do not increment the id since we are using UUIDs
     *
     * @var bool
     */
    public $incrementing = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'count'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'count' => 'integer'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|mixed
     */
    public function subscribeable()
    {
        return $this->morphTo();
    }
}