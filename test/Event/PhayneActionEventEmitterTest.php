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

namespace PhayneTest\Messaging\Event;

use Phayne\Messaging\Event\ActionEvent;
use Phayne\Messaging\Event\ListenerHandler;
use Phayne\Messaging\Event\PhayneActionEventEmitter;
use PhayneTest\Messaging\Mock\ActionEventListenerMock;
use PhayneTest\Messaging\Mock\ActionListenerAggregateMock;
use PHPUnit\Framework\TestCase;

/**
 * Class PhayneActionEventEmitterTest
 *
 * @package PhayneTest\Messaging\Event
 * @author Julien Guittard <julien.guittard@me.com>
 */
class PhayneActionEventEmitterTest extends TestCase
{
    private PhayneActionEventEmitter $actionEventEmitter;

    protected function setUp(): void
    {
        $this->actionEventEmitter = new PhayneActionEventEmitter();
    }

    public function testAttachesActionEventListenersAndDispatchEventToThem(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->param('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->actionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->actionEventEmitter->attachListener('test', $listener1);
        $this->actionEventEmitter->attachListener('test', $listener2);

        $this->actionEventEmitter->dispatch($actionEvent);

        $this->assertSame($lastEvent, $listener1->lastEvent);
    }

    public function testDetachesAListener(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->param('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->actionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $handler = $this->actionEventEmitter->attachListener('test', $listener1);
        $this->actionEventEmitter->attachListener('test', $listener2);
        $this->actionEventEmitter->detachListener($handler);
        $this->actionEventEmitter->dispatch($actionEvent);

        $this->assertNull($listener1->lastEvent);
        $this->assertSame($actionEvent, $lastEvent);
    }

    public function testTriggersListenersUntilCallbackReturnsTrue(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->param('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->actionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->actionEventEmitter->attachListener('test', $listener1);
        $this->actionEventEmitter->attachListener('test', $listener2);
        $this->actionEventEmitter->dispatchUntil($actionEvent, fn (ActionEvent $e) => true);

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    public function testStopsDispatchingWhenEventPropagationIsStopped(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) {
            $event->stopPropagation(true);
        };
        $listener3 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->param('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->actionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->actionEventEmitter->attachListener('test', $listener1);
        $this->actionEventEmitter->attachListener('test', $listener2);
        $this->actionEventEmitter->attachListener('test', $listener3);
        $this->actionEventEmitter->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    public function testStopsDispatchingWhenEventPropagationIsStopped2(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) {
        };
        $listener3 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->param('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->actionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->actionEventEmitter->attachListener('test', $listener1);
        $this->actionEventEmitter->attachListener('test', $listener2);
        $this->actionEventEmitter->attachListener('test', $listener3);

        $this->actionEventEmitter->dispatchUntil($actionEvent, function (ActionEvent $e) {
            $e->stopPropagation();
        });

        $this->assertNull($lastEvent);
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    public function testTriggersListenersWithHighPriorityFirst(): void
    {
        $lastEvent = null;
        $listener1 = new ActionEventListenerMock();
        $listener2 = function (ActionEvent $event) {
            $event->stopPropagation(true);
        };
        $listener3 = function (ActionEvent $event) use (&$lastEvent): void {
            if ($event->param('payload', false)) {
                $lastEvent = $event;
            }
        };

        $actionEvent = $this->actionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->actionEventEmitter->attachListener('test', $listener1, -100);
        $this->actionEventEmitter->attachListener('test', $listener3);
        $this->actionEventEmitter->attachListener('test', $listener2, 100);

        $this->actionEventEmitter->dispatch($actionEvent);

        $this->assertNull($lastEvent);
        $this->assertNull($listener1->lastEvent);
    }

    public function testAttachesAListenerAggregate(): void
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->actionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->actionEventEmitter->attachListener('test', $listener1);
        $this->actionEventEmitter->attachListenerAggregate($listenerAggregate);

        $this->actionEventEmitter->dispatch($actionEvent);

        $this->assertNull($listener1->lastEvent);
    }

    public function testDetachesListenerAggregate(): void
    {
        $listener1 = new ActionEventListenerMock();
        $listenerAggregate = new ActionListenerAggregateMock();

        $actionEvent = $this->actionEventEmitter->getNewActionEvent('test', $this, ['payload' => true]);

        $this->actionEventEmitter->attachListener('test', $listener1);
        $this->actionEventEmitter->attachListenerAggregate($listenerAggregate);
        $this->actionEventEmitter->detachListenerAggregate($listenerAggregate);

        $this->actionEventEmitter->dispatch($actionEvent);

        //If aggregate is not detached it would stop the event propagation and $listener1 would not be triggered
        $this->assertSame($actionEvent, $listener1->lastEvent);
    }

    public function testUsesDefaultEventNameIfNoneGiven(): void
    {
        $event = $this->actionEventEmitter->getNewActionEvent();
        $this->assertEquals('action_event', $event->name());
    }

    public function testReturnsFalseWhenUnattachedListenerHandlerGetsDetached(): void
    {
        $listener = $this->getMockForAbstractClass(ListenerHandler::class);

        $this->assertFalse($this->actionEventEmitter->detachListener($listener));
    }

    /*public function testDispatchesUntilWhithNoListenersAttached(): void
    {
        $actionEventMock = $this->createMock(ActionEvent::class);

        $this->actionEventEmitter->dispatchUntil($actionEventMock, fn () => true);
    }

    public function testAttachesToKnownEventNames(): void
    {
        $actionEventEmitter = new PhayneActionEventEmitter(['foo']);
        $actionEventEmitter->attachListener('foo', function (): void {
        });
    }*/

    public function testDoesNotAttachToUnknownEventNames(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown event name given: invalid');

        $actionEventEmitter = new PhayneActionEventEmitter(['foo']);
        $actionEventEmitter->attachListener('invalid', function (): void {
        });
    }
}
