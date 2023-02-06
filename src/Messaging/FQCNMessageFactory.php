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

use DateTimeImmutable;
use DateTimeZone;
use Phayne\Exception\UnexpectedValueException;
use Ramsey\Uuid\Uuid;

use function class_exists;
use function is_subclass_of;
use function sprintf;

/**
 * Class FQCNMessageFactory
 *
 * @package Phayne\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
class FQCNMessageFactory implements MessageFactory
{
    public function createMessageFromArray(string $messageName, array $messageData): Message
    {
        if (! class_exists($messageName)) {
            throw new UnexpectedValueException('Given message name is not a valid class: ' . $messageName);
        }

        if (! is_subclass_of($messageName, DomainMessage::class)) {
            throw new UnexpectedValueException(sprintf(
                'Message class %s is not a sub class of %s',
                $messageName,
                DomainMessage::class
            ));
        }

        if (! isset($messageData['message_name'])) {
            $messageData['message_name'] = $messageName;
        }

        if (! isset($messageData['uuid'])) {
            $messageData['uuid'] = Uuid::uuid4();
        }

        if (! isset($messageData['created_at'])) {
            $messageData['created_at'] = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        }

        if (! isset($messageData['metadata'])) {
            $messageData['metadata'] = [];
        }

        return $messageName::fromArray($messageData);
    }
}
