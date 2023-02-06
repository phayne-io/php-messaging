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
use Phayne\Messaging\Messaging\DomainMessage;
use PhayneTest\Messaging\Mock\AskSomething;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class QueryTest
 *
 * @package PhayneTest\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
class QueryTest extends TestCase
{
    public function testHasTheMessageTypeQuery(): void
    {
        $query = AskSomething::fromArray([
            'message_name' => 'TestQuery',
            'uuid' => Uuid::uuid4()->toString(),
            'created_at' => (new DateTimeImmutable('now', new DateTimeZone('UTC'))),
            'payload' => ['query' => 'payload'],
            'metadata' => ['query' => 'metadata'],
        ]);

        $this->assertEquals(DomainMessage::TYPE_QUERY, $query->messageType());
    }
}
