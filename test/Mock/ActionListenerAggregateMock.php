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

namespace PhayneTest\Messaging\Mock;

use Closure;
use Phayne\Messaging\Event\ActionEvent;
use Phayne\Messaging\Event\ActionEventEmitter;
use Phayne\Messaging\Event\ActionEventListenerAggregate;
use Phayne\Messaging\Event\DetachAggregateHandlers;

/**
 * Class ActionListenerAggregateMock
 *
 * @package PhayneTest\Messaging\Mock
 * @author Julien Guittard <julien.guittard@me.com>
 */
final class ActionListenerAggregateMock implements ActionEventListenerAggregate
{
    use DetachAggregateHandlers;

    public function attach(ActionEventEmitter $dispatcher): void
    {
        $callable = Closure::fromCallable([$this, 'onTest']);
        $this->trackHandler($dispatcher->attachListener('test', $callable, 100));
    }

    private function onTest(ActionEvent $event): void
    {
        $event->stopPropagation();
    }
}
