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

use ArrayObject;
use Phayne\Messaging\Event\DefaultActionEvent;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class DefaultActionEventTest
 *
 * @package PhayneTest\Messaging\Event
 * @author Julien Guittard <julien.guittard@me.com>
 */
class DefaultActionEventTest extends TestCase
{
    private function getTestEvent(): DefaultActionEvent
    {
        return new DefaultActionEvent('test-event', 'target', ['param1' => 'foo']);
    }

    public function testCanBeInitializedWithANameATargetAndParams(): void
    {
        $event = $this->getTestEvent();

        $this->assertEquals('test-event', $event->name());
        $this->assertEquals('target', $event->target());
        $this->assertEquals(['param1' => 'foo'], $event->params());
    }

    public function testCanInitializedWithoutATargetAndParams(): void
    {
        $event = new DefaultActionEvent('test-event');

        $this->assertNull($event->target());
        $this->assertEquals([], $event->params());
    }

    public function testReturnsParamIfSet(): void
    {
        $event = $this->getTestEvent();
        $this->assertEquals('foo', $event->param('param1'));
        $event->setParam('param1', 'bar');
        $this->assertEquals('bar', $event->param('param1'));
    }

    public function testReturnsNullIfParamIsNotSetAndNoOtherDefaultIsGiven(): void
    {
        $this->assertNull($this->getTestEvent()->param('unknown'));
    }

    public function testReturnsDefaultIfParamIsNotSet(): void
    {
        $this->assertEquals('default', $this->getTestEvent()->param('unknown', 'default'));
    }

    public function testChangesNameWhenNewOneIsSet(): void
    {
        $event = $this->getTestEvent();
        $event->setName('new name');
        $this->assertEquals('new name', $event->name());
    }

    public function testOverridesParamsArrayIfNewOneIsSet(): void
    {
        $event = $this->getTestEvent();

        $event->setParams(['param_new' => 'bar']);
        $this->assertEquals(['param_new' => 'bar'], $event->params());
    }

    public function testAllowsObjectImplementingArrayAccessAsParams(): void
    {
        $arrayLikeObject = new ArrayObject(['object_param' => 'baz']);

        $event = $this->getTestEvent();
        $event->setParams($arrayLikeObject);
        $this->assertSame($arrayLikeObject, $event->params());
    }

    public function testChangesTargetIfNewIsSet(): void
    {
        $event = $this->getTestEvent();

        $target = new stdClass();
        $event->setTarget($target);
        $this->assertSame($target, $event->target());
    }

    public function testIndicatesThatPropagationShouldBeStopped(): void
    {
        $event = $this->getTestEvent();

        $this->assertFalse($event->propagationIsStopped());
        $event->stopPropagation();
        $this->assertTrue($event->propagationIsStopped());
    }
}
