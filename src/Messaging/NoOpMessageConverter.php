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

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * Class NoOpMessageConverter
 *
 * @package Phayne\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
class NoOpMessageConverter implements MessageConverter
{
    /**
     * @throws AssertionFailedException
     */
    public function convertToArray(Message $domainMessage): array
    {
        Assertion::isInstanceOf($domainMessage, DomainMessage::class);
        return $domainMessage->toArray();
    }
}
