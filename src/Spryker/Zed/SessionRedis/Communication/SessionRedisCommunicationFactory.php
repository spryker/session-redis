<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis\Communication;

use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculator;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface;
use Spryker\Shared\SessionRedis\Handler\SessionAccountHandlerRedisInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactory;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapper;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReader;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReleaser;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReleaserInterface;
use Spryker\Zed\SessionRedis\SessionRedisDependencyProvider;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisCommunicationFactory extends AbstractCommunicationFactory
{
    public function createSessionRedisHandler(): SessionHandlerInterface
    {
        return $this->createSessionHandlerFactory()->createSessionRedisHandler(
            $this->createZedSessionRedisWrapper(),
        );
    }

    public function createSessionUserRedisHandler(): SessionAccountHandlerRedisInterface
    {
        return $this->createSessionHandlerFactory()->createSessionUserRedisHandler(
            $this->createZedSessionRedisWrapper(),
        );
    }

    public function createSessionHandlerRedisLocking(): SessionHandlerInterface
    {
        return $this->createSessionHandlerFactory()->createSessionHandlerRedisLocking(
            $this->createZedSessionRedisWrapper(),
        );
    }

    public function createZedSessionLockReleaser(): SessionLockReleaserInterface
    {
        $redisClient = $this->createZedSessionRedisWrapper();

        return new SessionLockReleaser(
            $this->createSessionHandlerFactory()->createSessionSpinLockLocker($redisClient),
            $this->createRedisSessionLockReader($redisClient),
        );
    }

    public function createYvesSessionLockReleaser(): SessionLockReleaserInterface
    {
        $redisClient = $this->createYvesSessionRedisWrapper();

        return new SessionLockReleaser(
            $this->createSessionHandlerFactory()->createSessionSpinLockLocker($redisClient),
            $this->createRedisSessionLockReader($redisClient),
        );
    }

    public function createRedisSessionLockReader(SessionRedisWrapperInterface $redisClient): SessionLockReaderInterface
    {
        return new SessionLockReader(
            $redisClient,
            $this->createSessionHandlerFactory()->createSessionKeyBuilder(),
        );
    }

    public function createZedSessionRedisWrapper(): SessionRedisWrapperInterface
    {
        return new SessionRedisWrapper(
            $this->getRedisClient(),
            $this->getConfig()->getZedRedisConnectionKey(),
            $this->getConfig()->getZedRedisConnectionConfiguration(),
        );
    }

    public function createYvesSessionRedisWrapper(): SessionRedisWrapperInterface
    {
        return new SessionRedisWrapper(
            $this->getRedisClient(),
            $this->getConfig()->getYvesRedisConnectionKey(),
            $this->getConfig()->getYvesRedisConnectionConfiguration(),
        );
    }

    public function createSessionHandlerFactory(): SessionHandlerFactoryInterface
    {
        return new SessionHandlerFactory(
            $this->getMonitoringService(),
            $this->createSessionRedisLifeTimeCalculator(),
            $this->getConfig()->getLockingTimeoutMilliseconds(),
            $this->getConfig()->getLockingRetryDelayMicroseconds(),
            $this->getConfig()->getLockingLockTtlMilliseconds(),
        );
    }

    public function createSessionRedisLifeTimeCalculator(): SessionRedisLifeTimeCalculatorInterface
    {
        return new SessionRedisLifeTimeCalculator(
            $this->getRequestStack(),
            $this->getSessionRedisLifeTimeCalculatorPlugins(),
            $this->getConfig()->getZedSessionLifeTime(),
        );
    }

    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::REQUEST_STACK);
    }

    public function getMonitoringService(): SessionRedisToMonitoringServiceInterface
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::SERVICE_MONITORING);
    }

    public function getRedisClient(): SessionRedisToRedisClientInterface
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::CLIENT_SESSION_REDIS);
    }

    /**
     * @return array<\Spryker\Shared\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface>
     */
    public function getSessionRedisLifeTimeCalculatorPlugins(): array
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR);
    }
}
