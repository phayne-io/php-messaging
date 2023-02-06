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

namespace Phayne\Messaging\Messaging;

/**
 * Interface MessageFactory
 *
 * @package Phayne\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
interface MessageFactory
{
    /**
     * Message data MUST contain at least a "payload" key
     * but may also contain "uuid", "message_name", "metadata", and "created_at".
     *
     * In general the message factory MUST support creating event objects from an array returned by
     * the corresponding Phayne\Messaging\MessageConverter
     *
     * You can use the assertion helper Phayne\Messaging\MessageDataAssertion to assert message data
     * before processing it.
     *
     * If one of the optional keys is not part of the message data the factory should use a default instead:
     * For example:
     * uuid = Uuid::uuid4()
     * message_name = $messageName //First parameter passed to the method
     * metadata = []
     * created_at = new \DateTimeImmutable('now', new \DateTimeZone('UTC')) //We treat all dates as UTC
     */
    public function createMessageFromArray(string $messageName, array $messageData): Message;
}
