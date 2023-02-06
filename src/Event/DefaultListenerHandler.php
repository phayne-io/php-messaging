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

use Assert\Assertion;
use Closure;

/**
 * Class DefaultListenerHandler
 *
 * @package Phayne\Messaging\Event
 * @author Julien Guittard <julien.guittard@me.com>
 */
class DefaultListenerHandler implements ListenerHandler
{
    public function __construct(private readonly mixed $listener)
    {
        Assertion::isCallable($this->listener, 'Listener must be callable');
    }

    public function actionEventListener(): callable
    {
        return $this->listener;
    }
}
