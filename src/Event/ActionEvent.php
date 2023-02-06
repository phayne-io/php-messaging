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
 * Interface ActionEvent
 *
 * An action event is mutable object used as a communication mechanism for ActionEventListeners listening on the same
 * event and performing actions based on the event and its current state.
 *
 * @package Phayne\Messaging\Event
 * @author Julien Guittard <julien.guittard@me.com>
 */
interface ActionEvent
{
    /**
     * Get the name of the action event
     *
     * @return string
     */
    public function name(): string;

    /**
     * Get target/context from which event was triggered
     *
     * @return null|string|object
     */
    public function target(): null | string | object;

    /**
     * Get parameters passed to the event
     *
     * @return array|ArrayAccess
     */
    public function params(): array | ArrayAccess;

    /**
     * Get a single parameter by name
     *
     * @param  string $name
     * @param  mixed $default Default value to return if parameter does not exist
     *
     * @return mixed
     */
    public function param(string $name, mixed $default = null): mixed;

    /**
     * Set the name of the action event
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;

    /**
     * Set the event target/context
     *
     * @param  null|string|object $target
     * @return void
     */
    public function setTarget(null | string | object $target): void;

    /**
     * Set event parameters
     *
     * @param  array|ArrayAccess $params
     * @return void
     */
    public function setParams(array | ArrayAccess $params): void;

    /**
     * Set a single parameter by key
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function setParam(string $name, mixed $value): void;

    /**
     * Indicate whether the parent ActionEventEmitter should stop propagating events
     *
     * @param  bool $flag
     * @return void
     */
    public function stopPropagation(bool $flag = true): void;

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function propagationIsStopped(): bool;
}
