<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientBridge;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\SessionRedis\SessionRedisConfig getConfig()
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
    public const CLIENT_SESSION_REDIS = 'CLIENT_SESSION_REDIS';

    /**
     * @var string
     */
    public const PLUGINS_HANDLER_SESSION = 'PLUGINS_HANDLER_SESSION';

    /**
     * @var string
     */
    public const PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR = 'PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR';

    /**
     * @var string
     */
    public const REQUEST_STACK = 'REQUEST_STACK';

    /**
     * @deprecated Use {@link \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK} instead.
     *
     * @var string
     */
    protected const REQUEST_STACK_CONTAINER_KEY = 'request_stack';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addRedisClient($container);
        $container = $this->addRequestStack($container);
        $container = $this->addSessionRedisLifeTimeCalculatorPlugins($container);

        return $container;
    }

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addRedisClient($container);

        return $container;
    }

    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::REQUEST_STACK, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    protected function addMonitoringService(Container $container): Container
    {
        $container->set(static::SERVICE_MONITORING, function (Container $container) {
            $sessionToMonitoringServiceBridge = new SessionRedisToMonitoringServiceBridge(
                $container->getLocator()->monitoring()->service(),
            );

            return $sessionToMonitoringServiceBridge;
        });

        return $container;
    }

    protected function addRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION_REDIS, function (Container $container) {
            return new SessionRedisToRedisClientBridge(
                $container->getLocator()->redis()->client(),
            );
        });

        return $container;
    }

    protected function addSessionRedisLifeTimeCalculatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR, function () {
            return $this->getSessionRedisLifeTimeCalculatorPlugins();
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
}
