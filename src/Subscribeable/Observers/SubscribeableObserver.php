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

namespace Gox\Laravel\Subscribe\Subscribeable\Observers;

use Gox\Contracts\Subscribe\Subscribeable\Models\Subscribeable as SubscribeableContract;


class SubscribeableObserver
{

    /**
     * @param SubscribeableContract $subscribeable
     */
    public function deleted(SubscribeableContract $subscribeable)
    {
        if ( !$this->removeSubscribesOnDelete($subscribeable) ) {
            return;
        }

        $subscribeable->removeSubscribes();
    }

    /**
     * @param SubscribeableContract $subscribeable
     * @return bool
     */
    private function removeSubscribesOnDelete(SubscribeableContract $subscribeable): bool
    {
        return $subscribeable->removeSubscribesOnDelete ?? true;
    }
}