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

use Assert\InvalidArgumentException;
use Phayne\Messaging\Messaging\DomainMessage;
use Phayne\Messaging\Messaging\Message;
use Phayne\Messaging\Messaging\NoOpMessageConverter;
use PHPUnit\Framework\TestCase;

/**
 * Class NoOpMessageConverterTest
 *
 * @package PhayneTest\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
class NoOpMessageConverterTest extends TestCase
{
    public function testConvertsToArray(): void
    {
        $messageMock = $this->getMockForAbstractClass(DomainMessage::class, [], '', true, true, true, ['toArray']);
        $messageMock->expects($this->once())->method('toArray');

        $converter = new NoOpMessageConverter();
        $converter->convertToArray($messageMock);
    }

    /**
     * @test
     */
    public function testThrowsExceptionWhenMessageIsNotADomainMessage(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $messageMock = $this->getMockForAbstractClass(Message::class);

        $converter = new NoOpMessageConverter();
        $converter->convertToArray($messageMock);
    }
}
