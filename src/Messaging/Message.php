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
use Ramsey\Uuid\UuidInterface;

/**
 * Interface Message
 *
 * @package Phayne\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
interface Message extends HasMessageName
{
    public const TYPE_COMMAND = 'command';

    public const TYPE_EVENT = 'event';

    public const TYPE_QUERY = 'query';

    /**
     * Should be one of Message::TYPE_COMMAND, Message::TYPE_EVENT or Message::TYPE_QUERY
     */
    public function messageType(): string;

    public function uuid(): UuidInterface;

    public function createdAt(): DateTimeImmutable;

    public function payload(): array;

    public function metadata(): array;

    public function withMetadata(array $metadata): Message;

    public function withAddedMetadata(string $key, $value): Message;
}
