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
 * Interface MessageConverter
 *
 * @package Phayne\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
interface MessageConverter
{
    /**
     * The result array MUST contain the following data structure:
     *
     * [
     *   'message_name' => string,
     *   'uuid' => string,
     *   'payload' => array, //MUST only contain sub arrays and/or scalar types, objects, etc. are not allowed!
     *   'metadata' => array, //MUST only contain key/value pairs with values being only scalar types!
     *   'created_at' => \DateTimeInterface,
     * ]
     *
     * The correct structure and types are asserted by MessageDataAssertion::assert()
     * so make sure that the returned array of your custom conversion logic passes the assertion.
     */
    public function convertToArray(Message $domainMessage): array;
}
