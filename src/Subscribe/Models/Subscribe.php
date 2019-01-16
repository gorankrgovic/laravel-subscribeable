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

namespace Gox\Laravel\Subscribe\Subscribe\Models;

use Gox\Contracts\Subscribe\Subscribe\Models\Subscribe as SubscribeContract;
use Gox\Laravel\Subscribe\UuidTrait\GenerateUuid;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model implements SubscribeContract
{

    use GenerateUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscribes';


    /**
     * Since we are using the UUID as the ID we are not incrementing the model
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
        'user_id'
    ];



    /**
     * Subscribeable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subscribeable()
    {
        return $this->morphTo();
    }
}