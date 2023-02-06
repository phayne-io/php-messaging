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

use ArrayAccess;

/**
 * Interface ActionEventEmitter
 *
 * An action event emitter dispatches ActionEvents which are mutable objects used as a communication mechanism
 * between ActionEventListeners listening on the same event and performing actions based on it.
 *
 * @package Phayne\Messaging\Event
 * @author Julien Guittard <julien.guittard@me.com>
 */
interface ActionEventEmitter
{
    /**
     * @param string $name of the action event
     * @param null|string|object $target of the action event
     * @param array|ArrayAccess $params with which the event is initialized
     *
     * @return ActionEvent that can be triggered by the ActionEventEmitter
     */
    public function getNewActionEvent(
        string $name = 'action_event',
        null | string | object $target = null,
        array|ArrayAccess $params = []
    ): ActionEvent;

    public function dispatch(ActionEvent $event): void;

    /**
     * Trigger an event until the given callback returns a boolean true
     *
     * The callback is invoked after each listener and gets the action event as only argument
     */
    public function dispatchUntil(ActionEvent $event, callable $callback): void;

    public function attachListener(string $event, callable $listener, int $priority = 1): ListenerHandler;

    public function detachListener(ListenerHandler $listenerHandler): bool;

    public function attachListenerAggregate(ActionEventListenerAggregate $aggregate): void;

    public function detachListenerAggregate(ActionEventListenerAggregate $aggregate): void;
}
