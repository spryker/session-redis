<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Checker;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use Spryker\Yves\SessionRedis\SessionRedisConfig;

class UrlSessionRedisLockingInclusionChecker implements UrlSessionRedisLockingInclusionCheckerInterface
{
    public function __construct(protected SessionRedisConfig $sessionRedisConfig)
    {
    }

    public function checkCondition(
        RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer
    ): bool {
        $includedUrlPatterns = $this->sessionRedisConfig->getSessionRedisLockingIncludedUrlPatterns();

        if (!$includedUrlPatterns) {
            return false;
        }

        if (!$redisLockingSessionHandlerConditionTransfer->getRequestUri()) {
            return false;
        }

        $combinedPattern = '/(?:' . implode(')|(?:', array_map(
            fn (string $p) => substr($p, 1, -1),
            $includedUrlPatterns,
        )) . ')/';

        return !preg_match($combinedPattern, $redisLockingSessionHandlerConditionTransfer->getRequestUri());
    }
}
