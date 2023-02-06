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
use Phayne\Exception\UnexpectedValueException;
use Phayne\Messaging\Messaging\FQCNMessageFactory;
use PhayneTest\Messaging\Mock\DoSomething;
use PhayneTest\Messaging\Mock\InvalidMessage;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class FQCNMessageFactoryTest
 *
 * @package PhayneTest\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
class FQCNMessageFactoryTest extends TestCase
{
    private FQCNMessageFactory $messageFactory;

    protected function setUp(): void
    {
        $this->messageFactory = new FQCNMessageFactory();
    }

    public function testCreatesANewMessageFromArrayAndFqcn(): void
    {
        $uuid = Uuid::uuid4();
        $createdAt = new DateTimeImmutable();

        $command = $this->messageFactory->createMessageFromArray(DoSomething::class, [
            'uuid' => $uuid->toString(),
            'payload' => ['command' => 'payload'],
            'metadata' => ['command' => 'metadata'],
            'created_at' => $createdAt,
        ]);

        $this->assertEquals(DoSomething::class, $command->messageName());
        $this->assertEquals($uuid->toString(), $command->uuid()->toString());
        $this->assertEquals($createdAt, $command->createdAt());
        $this->assertEquals(['command' => 'payload'], $command->payload());
        $this->assertEquals(['command' => 'metadata'], $command->metadata());
    }

    public function testCreatesANewMessageWithDefaultsFromArrayAndFqcn(): void
    {
        $command = $this->messageFactory->createMessageFromArray(DoSomething::class, [
            'payload' => ['command' => 'payload'],
        ]);

        $this->assertEquals(DoSomething::class, $command->messageName());
        $this->assertEquals(['command' => 'payload'], $command->payload());
        $this->assertEquals([], $command->metadata());
    }

    public function testThrowsExceptionWhenMessageClassCannotBeFound(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $this->messageFactory->createMessageFromArray('NotExistingClass', []);
    }

    public function testThrowsExceptionWhenMessageClassIsNotASubClassDomainMessage(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $this->messageFactory->createMessageFromArray(InvalidMessage::class, []);
    }
}
