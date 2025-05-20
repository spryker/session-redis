<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientBridge;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_MONITORING = 'SERVICE_MONITORING';

    /**
     * @var string
     */
    public const CLIENT_REDIS = 'CLIENT_REDIS';

    /**
     * @var string
     */
    public const PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR = 'PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR';

    /**
     * @var string
     */
    public const REQUEST_STACK = 'REQUEST_STACK';

    /**
     * @var string
     */
    protected const REQUEST_STACK_CONTAINER_KEY = 'request_stack';

    /**
     * @var string
     */
    public const PLUGINS_SESSION_REDIS_LOCKING_EXCLUSION_CONDITION = 'PLUGINS_CONFIGURABLE_REDIS_LOCKING_SESSION_HANDLER_CONDITION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addRedisClient($container);
        $container = $this->addSessionRedisLifeTimeCalculatorPlugins($container);
        $container = $this->addRequestStack($container);
        $container = $this->addSessionRedisLockingExclusionConditionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_REDIS, function (Container $container) {
            return new SessionRedisToRedisClientBridge(
                $container->getLocator()->redis()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMonitoringService(Container $container): Container
    {
        $container->set(static::SERVICE_MONITORING, function () use ($container) {
            return new SessionRedisToMonitoringServiceBridge(
                $container->getLocator()->monitoring()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::REQUEST_STACK, function (ContainerInterface $container) {
            return $container->getApplicationService(static::REQUEST_STACK_CONTAINER_KEY);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionRedisLifeTimeCalculatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR, function () {
            return $this->getSessionRedisLifeTimeCalculatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionRedisLockingExclusionConditionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SESSION_REDIS_LOCKING_EXCLUSION_CONDITION, function () {
            return $this->getSessionRedisLockingExclusionConditionPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface>
     */
    protected function getSessionRedisLifeTimeCalculatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Yves\SessionRedisExtension\Dependency\Plugin\SessionRedisLockingExclusionConditionPluginInterface>
     */
    protected function getSessionRedisLockingExclusionConditionPlugins(): array
    {
        return [];
    }
}
