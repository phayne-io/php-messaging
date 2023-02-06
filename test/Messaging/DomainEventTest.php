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

namespace PhayneTest\Messaging\Messaging;

use DateTimeImmutable;
use DateTimeZone;
use Phayne\Messaging\Messaging\DomainEvent;
use Phayne\Messaging\Messaging\DomainMessage;
use PhayneTest\Messaging\Mock\SomethingWasDone;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class DomainEventTest
 *
 * @package PhayneTest\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
class DomainEventTest extends TestCase
{
    private DomainEvent $domainEvent;

    private DateTimeImmutable $createdAt;

    private UuidInterface $uuid;

    protected function setUp(): void
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        $this->domainEvent = SomethingWasDone::fromArray([
            'message_name' => 'TestDomainEvent',
            'uuid' => $this->uuid->toString(),
            'created_at' => $this->createdAt,
            'payload' => ['event' => 'payload'],
            'metadata' => ['event' => 'metadata'],
        ]);
    }

    public function testHasAName(): void
    {
        $this->assertEquals('TestDomainEvent', $this->domainEvent->messageName());
    }

    public function testHasAUuid(): void
    {
        $this->assertTrue($this->uuid->equals($this->domainEvent->uuid()));
    }

    public function testHasCreatedAtInformation(): void
    {
        $this->assertEquals(
            $this->createdAt->format(DateTimeImmutable::ATOM),
            $this->domainEvent->createdAt()->format(DateTimeImmutable::ATOM)
        );
    }

    public function testHasPayload(): void
    {
        $this->assertEquals(['event' => 'payload'], $this->domainEvent->payload());
    }

    public function testHasMetadata(): void
    {
        $this->assertEquals(['event' => 'metadata'], $this->domainEvent->metadata());
    }

    public function testCanBeConvertedToArrayAndBack(): void
    {
        $commandData = $this->domainEvent->toArray();
        $commandCopy = SomethingWasDone::fromArray($commandData);

        $this->assertEquals($commandData, $commandCopy->toArray());
    }

    public function testIsOfTypeEvent(): void
    {
        $this->assertEquals(DomainMessage::TYPE_EVENT, $this->domainEvent->messageType());
    }
}
