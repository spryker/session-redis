<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\SessionRedisExtension\Dependency\Plugin\SessionRedisLockingExclusionConditionPluginInterface;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 */
class UrlSessionRedisLockingInclusionConditionPlugin extends AbstractPlugin implements SessionRedisLockingExclusionConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Enforces session locking only for URLs that match patterns configured in {@link \Spryker\Yves\SessionRedis\SessionRedisConfig::getSessionRedisLockingIncludedUrlPatterns()}.
     * - Returns `false` when no inclusion patterns are configured, leaving locking decisions to other plugins.
     * - Returns `false` when the URI matches a configured pattern, keeping the session lock active.
     * - Returns `true` when the URI does not match any pattern, bypassing the session lock for that request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer
     *
     * @return bool
     */
    public function checkCondition(RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer): bool
    {
        return $this->getFactory()->createUrlSessionRedisLockingInclusionChecker()->checkCondition($redisLockingSessionHandlerConditionTransfer);
    }
}
