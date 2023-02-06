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
use Phayne\Messaging\Messaging\Command;
use Phayne\Messaging\Messaging\DomainMessage;
use PhayneTest\Messaging\Mock\DoSomething;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class CommandTest
 *
 * @package PhayneTest\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
class CommandTest extends TestCase
{
    private Command $command;

    private DateTimeImmutable $createdAt;

    private UuidInterface $uuid;

    protected function setUp(): void
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        $this->command = DoSomething::fromArray([
            'message_name' => 'TestCommand',
            'uuid' => $this->uuid->toString(),
            'created_at' => $this->createdAt,
            'payload' => ['command' => 'payload'],
            'metadata' => ['command' => 'metadata'],
        ]);
    }

    public function testHasAName(): void
    {
        $this->assertEquals('TestCommand', $this->command->messageName());
    }

    public function testHasAUuid(): void
    {
        $this->assertTrue($this->uuid->equals($this->command->uuid()));
    }

    public function testHasCreatedAtInformation(): void
    {
        $this->assertEquals(
            $this->createdAt->format(DateTimeImmutable::ATOM),
            $this->command->createdAt()->format(DateTimeImmutable::ATOM)
        );
    }

    public function testHasPayload(): void
    {
        $this->assertEquals(['command' => 'payload'], $this->command->payload());
    }

    public function testHasMetadata(): void
    {
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
    }

    public function testCanBeConvertedToArrayAndBack(): void
    {
        $commandData = $this->command->toArray();
        $commandCopy = DoSomething::fromArray($commandData);

        $this->assertEquals($commandData, $commandCopy->toArray());
    }

    public function testReturnsNewInstanceWithReplacedMetadata(): void
    {
        $newCommand = $this->command->withMetadata(['other' => 'metadata']);

        $this->assertNotSame($this->command, $newCommand);
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
        $this->assertEquals(['other' => 'metadata'], $newCommand->metadata());
    }

    public function testReturnsNewInstanceWithAddedMetadata(): void
    {
        $newCommand = $this->command->withAddedMetadata('other', 'metadata');

        $this->assertNotSame($this->command, $newCommand);
        $this->assertEquals(['command' => 'metadata'], $this->command->metadata());
        $this->assertEquals(['command' => 'metadata', 'other' => 'metadata'], $newCommand->metadata());
    }

    public function testIsInitializedWithDefaults(): void
    {
        $command = new DoSomething(['command' => 'payload']);

        $this->assertEquals(DoSomething::class, $command->messageName());
        $this->assertInstanceOf(UuidInterface::class, $command->uuid());
        $this->assertEquals((new DateTimeImmutable())->format('Y-m-d'), $command->createdAt()->format('Y-m-d'));
        $this->assertEquals(['command' => 'payload'], $command->payload());
        $this->assertEquals([], $command->metadata());
    }

    public function testIsOfTypeCommand(): void
    {
        $this->assertEquals(DomainMessage::TYPE_COMMAND, $this->command->messageType());
    }
}
