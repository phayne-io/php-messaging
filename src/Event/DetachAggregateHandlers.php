<?php

/**
 * This file is part of phayne-io/php-messaging package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see       https://github.com/phayne-io/php-messaging for the canonical source repository
 * @copyright Copyright (c) 2023 Phayne. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\Messaging\Event;

/**
 * Trait DetachAggregateHandlers
 *
 * @package Phayne\Messaging\Event
 * @author Julien Guittard <julien.guittard@me.com>
 */
trait DetachAggregateHandlers
{
    /**
     * @var ListenerHandler[]
     */
    private array $handlerCollection = [];

    protected function trackHandler(ListenerHandler $handler): void
    {
        $this->handlerCollection[] = $handler;
    }

    public function detach(ActionEventEmitter $dispatcher): void
    {
        foreach ($this->handlerCollection as $handler) {
            $dispatcher->detachListener($handler);
        }
    }
}
