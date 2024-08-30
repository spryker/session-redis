<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\Session;

use SessionHandlerInterface;
use Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()()
 */
class SessionHandlerRedisWriteOnlyLockingProviderPlugin extends AbstractPlugin implements SessionHandlerProviderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getSessionHandlerName(): string
    {
        return $this->getConfig()->getSessionHandlerRedisWriteOnlyLockingName();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \SessionHandlerInterface
     */
    public function getSessionHandler(): SessionHandlerInterface
    {
        return $this->getFactory()->createSessionHandlerRedisWriteOnlyLocking();
    }
}
