<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Spryker\Shared\SessionRedis\SessionRedisConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\SessionRedis\SessionRedisConfig getSharedConfig()
 */
class SessionRedisConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const SESSION_REDIS_CONNECTION_KEY = 'SESSION_YVES';

    /**
     * @api
     *
     * @return int
     */
    public function getSessionLifetime(): int
    {
        return $this->get(SessionRedisConstants::YVES_SESSION_TIME_TO_LIVE, 30);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getLockingTimeoutMilliseconds(): int
    {
        return $this->getSharedConfig()->getLockingTimeoutMilliseconds();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getLockingRetryDelayMicroseconds(): int
    {
        return $this->getSharedConfig()->getLockingRetryDelayMicroseconds();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getLockingLockTtlMilliseconds(): int
    {
        return $this->getSharedConfig()->getLockingLockTtlMilliseconds();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\RedisConfigurationTransfer
     */
    public function getRedisConnectionConfiguration(): RedisConfigurationTransfer
    {
        return (new RedisConfigurationTransfer())
            ->setDataSourceNames(
                $this->getDataSourceNames(),
            )
            ->setConnectionCredentials(
                $this->getConnectionCredentials(),
            )
            ->setClientOptions(
                $this->getConnectionOptions(),
            );
    }

    /**
     * @api
     *
     * @return string
     */
    public function getRedisConnectionKey(): string
    {
        return static::SESSION_REDIS_CONNECTION_KEY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSessionHandlerRedisName(): string
    {
        return $this->getSharedConfig()->getSessionHandlerRedisName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSessionHandlerRedisLockingName(): string
    {
        return $this->getSharedConfig()->getSessionHandlerRedisLockingName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSessionHandlerRedisWriteOnlyLockingName(): string
    {
        return $this->getSharedConfig()->getSessionHandlerRedisWriteOnlyLockingName();
    }

    /**
     * @return array<string>
     */
    protected function getDataSourceNames(): array
    {
        return $this->get(SessionRedisConstants::YVES_SESSION_REDIS_DATA_SOURCE_NAMES, []);
    }

    /**
     * @return \Generated\Shared\Transfer\RedisCredentialsTransfer
     */
    protected function getConnectionCredentials(): RedisCredentialsTransfer
    {
        return (new RedisCredentialsTransfer())
            ->setScheme($this->getScheme())
            ->setHost($this->get(SessionRedisConstants::YVES_SESSION_REDIS_HOST))
            ->setPort($this->get(SessionRedisConstants::YVES_SESSION_REDIS_PORT))
            ->setDatabase($this->get(SessionRedisConstants::YVES_SESSION_REDIS_DATABASE))
            ->setPassword($this->get(SessionRedisConstants::YVES_SESSION_REDIS_PASSWORD, false));
    }

    /**
     * @deprecated Use $this->get(SessionRedisConstants::YVES_SESSION_REDIS_SCHEME) instead. Added for BC reason only.
     *
     * @return string
     */
    protected function getScheme(): string
    {
        return $this->get(SessionRedisConstants::YVES_SESSION_REDIS_SCHEME, false) ?:
            $this->get(SessionRedisConstants::YVES_SESSION_REDIS_PROTOCOL);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConnectionOptions(): array
    {
        return $this->get(SessionRedisConstants::YVES_SESSION_REDIS_CLIENT_OPTIONS, []);
    }
}
