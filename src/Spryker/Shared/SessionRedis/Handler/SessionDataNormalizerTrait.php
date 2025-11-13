<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Shared\SessionRedis\Handler;

trait SessionDataNormalizerTrait
{
    protected function normalizeSessionData(?string $sessionData = null): string
    {
        if (!$sessionData) {
            return '';
        }

        $sessionData = $this->tryDecodeLegacySession($sessionData);

        if ($sessionData === null) {
            return '';
        }

        return $sessionData;
    }

    protected function tryDecodeLegacySession(string $sessionData): ?string
    {
        if (substr($sessionData, 0, 1) === '"') {
            return json_decode($sessionData, true);
        }

        return $sessionData;
    }
}
