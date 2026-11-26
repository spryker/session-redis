<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Shared\SessionRedis\Handler;

trait SessionIdValidationTrait
{
    protected ?string $prefetchedSessionId = null;

    protected ?string $prefetchedSessionData = null;

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

        $this->prefetchedSessionId = $sessionId;
        $this->prefetchedSessionData = $sessionData;

        return true;
    }

    protected function hasPrefetchedSessionData(string $sessionId): bool
    {
        return $this->prefetchedSessionId === $sessionId;
    }

    protected function readSessionData(string $sessionId): ?string
    {
        if (!$this->hasPrefetchedSessionData($sessionId)) {
            return $this->redisClient->get($this->keyBuilder->buildSessionKey($sessionId));
        }

        $sessionData = $this->prefetchedSessionData;
        $this->prefetchedSessionId = null;
        $this->prefetchedSessionData = null;

        return $sessionData;
    }
}
