<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Shared\SessionRedis\Handler;

trait SessionIdValidationTrait
{
    /**
     * @var array<string, string>
     */
    protected array $prefetchedSessionDataBySessionId = [];

    public function validateId(string $id): bool
    {
        return $this->prefetchSessionData($id);
    }

    public function updateTimestamp(string $id, string $data): bool
    {
        return $this->write($id, $data);
    }

    protected function prefetchSessionData(string $sessionId): bool
    {
        $sessionData = $this->redisClient->get($this->keyBuilder->buildSessionKey($sessionId));

        if ($sessionData === null) {
            return false;
        }

        $this->prefetchedSessionDataBySessionId[$sessionId] = $sessionData;

        return true;
    }

    protected function hasPrefetchedSessionData(string $sessionId): bool
    {
        return array_key_exists($sessionId, $this->prefetchedSessionDataBySessionId);
    }

    protected function readSessionData(string $sessionId): ?string
    {
        if (!$this->hasPrefetchedSessionData($sessionId)) {
            return $this->redisClient->get($this->keyBuilder->buildSessionKey($sessionId));
        }

        $sessionData = $this->prefetchedSessionDataBySessionId[$sessionId];
        unset($this->prefetchedSessionDataBySessionId[$sessionId]);

        return $sessionData;
    }
}
