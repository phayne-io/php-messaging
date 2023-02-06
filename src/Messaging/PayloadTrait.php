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
 * Trait PayloadTrait
 *
 * @package Phayne\Messaging\Messaging
 * @author Julien Guittard <julien.guittard@me.com>
 */
trait PayloadTrait
{
    public function __construct(protected array $payload = [])
    {
        $this->init();
    }

    public function payload(): array
    {
        return $this->payload;
    }

    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * Use this method to initialize message with defaults or extend your class from DomainMessage
     */
    abstract protected function init(): void;
}
