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

namespace Phayne\Messaging\Event;

use ArrayAccess;

/**
 * Class DefaultActionEvent
 *
 * @package Phayne\Messaging\Event
 * @author Julien Guittard <julien.guittard@me.com>
 */
class DefaultActionEvent implements ActionEvent
{
    protected bool $stopPropagation = false;

    public function __construct(
        protected string $name,
        protected null | string | object $target = null,
        protected array | ArrayAccess $params = []
    ) {
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function target(): null|string|object
    {
        return $this->target;
    }

    /**
     * @inheritDoc
     */
    public function params(): array|ArrayAccess
    {
        return $this->params;
    }

    /**
     * @inheritDoc
     */
    public function param(string $name, mixed $default = null): mixed
    {
        return $this->params[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function setTarget(object|string|null $target): void
    {
        $this->target = $target;
    }

    /**
     * @inheritDoc
     */
    public function setParams(ArrayAccess|array $params): void
    {
        $this->params = $params;
    }

    /**
     * @inheritDoc
     */
    public function setParam(string $name, mixed $value): void
    {
        $this->params[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function stopPropagation(bool $flag = true): void
    {
        $this->stopPropagation = $flag;
    }

    /**
     * @inheritDoc
     */
    public function propagationIsStopped(): bool
    {
        return $this->stopPropagation;
    }
}
