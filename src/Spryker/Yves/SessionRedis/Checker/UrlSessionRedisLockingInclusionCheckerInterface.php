<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Checker;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;

interface UrlSessionRedisLockingInclusionCheckerInterface
{
    /**
     * Checks whether session locking should be skipped for the current request URI.
     *
     * When inclusion patterns are configured, locking is applied only to matching URLs.
     * Any URL that does not match a configured pattern is excluded from locking.
     * When no patterns are configured, this checker has no effect (returns false).
     */
    public function checkCondition(RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer): bool;
}
