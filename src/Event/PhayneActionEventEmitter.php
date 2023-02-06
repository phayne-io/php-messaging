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
use Assert\Assertion;
use Phayne\Exception\InvalidArgumentException;

use function in_array;
use function krsort;
use function sprintf;

/**
 * Class PhayneActionEventEmitter
 *
 * @package Phayne\Messaging\Event
 * @author Julien Guittard <julien.guittard@me.com>
 */
class PhayneActionEventEmitter implements ActionEventEmitter
{
    /**
     * Map of event name to listeners array
     *
     * @var array
     */
    protected array $events = [];

    public function __construct(protected readonly array $availableEventNames = [])
    {
        Assertion::allString($this->availableEventNames, 'Available event names must be an array of strings');
    }

    /**
     * @inheritDoc
     */
    public function getNewActionEvent(
        string $name = 'action_event',
        object|string|null $target = null,
        ArrayAccess|array $params = []
    ): ActionEvent {
        return new DefaultActionEvent($name, $target, $params);
    }

    public function dispatch(ActionEvent $event): void
    {
        foreach ($this->listeners($event) as $listenerHandler) {
            $listener = $listenerHandler->actionEventListener();
            $listener($event);
            if ($event->propagationIsStopped()) {
                return;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function dispatchUntil(ActionEvent $event, callable $callback): void
    {
        foreach ($this->listeners($event) as $listenerHandler) {
            $listener = $listenerHandler->actionEventListener();
            $listener($event);

            if ($event->propagationIsStopped()) {
                return;
            }
            if ($callback($event) === true) {
                return;
            }
        }
    }

    public function attachListener(string $event, callable $listener, int $priority = 1): ListenerHandler
    {
        if (! empty($this->availableEventNames) && ! in_array($event, $this->availableEventNames, true)) {
            throw new InvalidArgumentException(sprintf('Unknown event name given: %s', $event));
        }

        $handler = new DefaultListenerHandler($listener);

        $this->events[$event][($priority) . '.0'][] = $handler;

        return $handler;
    }

    public function detachListener(ListenerHandler $listenerHandler): bool
    {
        foreach ($this->events as &$prioritizedListeners) {
            foreach ($prioritizedListeners as &$listenerHandlers) {
                foreach ($listenerHandlers as $index => $listedListenerHandler) {
                    if ($listedListenerHandler === $listenerHandler) {
                        unset($listenerHandlers[$index]);

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function attachListenerAggregate(ActionEventListenerAggregate $aggregate): void
    {
        $aggregate->attach($this);
    }

    public function detachListenerAggregate(ActionEventListenerAggregate $aggregate): void
    {
        $aggregate->detach($this);
    }

    /**
     * @param ActionEvent $event
     * @return ListenerHandler[]
     */
    private function listeners(ActionEvent $event): iterable
    {
        $prioritizedListeners = $this->events[$event->name()] ?? [];

        krsort($prioritizedListeners, SORT_NUMERIC);

        foreach ($prioritizedListeners as $listenersByPriority) {
            foreach ($listenersByPriority as $listenerHandler) {
                yield $listenerHandler;
            }
        }
    }
}
