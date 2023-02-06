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
use Assert\InvalidArgumentException;
use Phayne\Messaging\Messaging\MessageDataAssertion;
use Phayne\Messaging\Messaging\NoOpMessageConverter;
use PhayneTest\Messaging\Mock\DoSomething;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

/**
 * Class MessageDataAssertionTest
 *
 * @package PhayneTest\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
class MessageDataAssertionTest extends TestCase
{
    public function testAssertsMessageDataReturnedByTheNoOpMessageConverter(): void
    {
        $testAssertions = new DoSomething(['test' => 'assertions', ['null' => null]]);

        $messageConverter = new NoOpMessageConverter();

        MessageDataAssertion::assert($messageConverter->convertToArray($testAssertions));

        //No exception thrown means test green
        $this->assertTrue(true);
    }

    /**
     * @dataProvider
     * @dataProvider provideMessageDataWithMissingKey
     */
    public function testThrowsExceptionIfMessageDataIsInvalid($messageData, $errorMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($errorMessage);

        MessageDataAssertion::assert($messageData);
    }

    public function provideMessageDataWithMissingKey()
    {
        $uuid = Uuid::uuid4()->toString();
        $payload = ['foo' => ['bar' => ['baz' => 100]]];
        $metadata = ['key' => 'value', 'string' => 'scalar'];
        $createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        return [
            [
                'message-data',
                'MessageData must be an array',
            ],
            [
                //#1 uuid is missing
                [
                    'message_name' => 'test-message',
                    'payload' => $payload,
                    'metadata' => $metadata,
                    'created_at' => $createdAt
                ],
                'MessageData must contain a key uuid',
            ],
            [
                //#2 message_name missing
                ['uuid' => $uuid, 'payload' => $payload, 'metadata' => $metadata, 'created_at' => $createdAt],
                'MessageData must contain a key message_name',
            ],
            [
                //#3 payload missing
                [
                    'uuid' => $uuid,
                    'message_name' => 'test-message',
                    'metadata' => $metadata,
                    'created_at' => $createdAt
                ],
                'MessageData must contain a key payload',
            ],
            [
                //#4 metadata missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => $payload, 'created_at' => $createdAt],
                'MessageData must contain a key metadata',
            ],
            [
                //#5 created at missing
                ['uuid' => $uuid, 'message_name' => 'test-message', 'payload' => $payload, 'metadata' => $metadata],
                'MessageData must contain a key created_at',
            ],
            [
                //#6 invalid uuid string
                [
                    'uuid' => 'invalid',
                    'message_name' => 'test-message',
                    'payload' => $payload,
                    'metadata' => $metadata,
                    'created_at' => $createdAt
                ],
                'uuid must be a valid UUID string',
            ],
            [
                //#7 message name to short
                [
                    'uuid' => $uuid,
                    'message_name' => 'te',
                    'payload' => $payload,
                    'metadata' => $metadata,
                    'created_at' => $createdAt
                ],
                'message_name must be string with at least 3 chars length',
            ],
            [
                //#8 payload must be an array
                [
                    'uuid' => $uuid,
                    'message_name' => 'test-message',
                    'payload' => 'string',
                    'metadata' => $metadata,
                    'created_at' => $createdAt
                ],
                'payload must be an array',
            ],
            [
                //#9 payload must not contain objects
                [
                    'uuid' => $uuid,
                    'message_name' => 'test-message',
                    'payload' => [
                        'sub' => [
                            'key' => new stdClass()
                        ]
                    ],
                    'metadata' => $metadata,
                    'created_at' => $createdAt
                ],
                'payload must only contain arrays and scalar values',
            ],
            [
                //#10 metadata must be an array
                [
                    'uuid' => $uuid,
                    'message_name' => 'test-message',
                    'payload' => $payload,
                    'metadata' => 'string',
                    'created_at' => $createdAt
                ],
                'metadata must be an array',
            ],
            [
                //#11 metadata must not contain non scalar values
                [
                    'uuid' => $uuid,
                    'message_name' => 'test-message',
                    'payload' => $payload,
                    'metadata' => [
                        'sub_array' => []
                    ],
                    'created_at' => $createdAt
                ],
                'A metadata value must have a scalar type. Got array for sub_array',
            ],
            [
                //#12 created_at must be of type \DateTimeImmutable
                [
                    'uuid' => $uuid,
                    'message_name' => 'test-message',
                    'payload' => $payload,
                    'metadata' => $metadata,
                    'created_at' => '2015-08-25 16:30:10'
                ],
                'created_at must be of type DateTimeImmutable. Got string',
            ],
        ];
    }
}
