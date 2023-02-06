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

namespace PhayneTest\Messaging\Mock;

use Phayne\Messaging\Event\ActionEvent;

/**
 * Class ActionEventListenerMock
 *
 * @package PhayneTest\Messaging\Mock
 * @author Julien Guittard <julien.guittard@me.com>
 */
final class ActionEventListenerMock
{
    public ?ActionEvent $lastEvent = null;

    public function __invoke(ActionEvent $event): void
    {
        $this->lastEvent = $event;
    }
}
