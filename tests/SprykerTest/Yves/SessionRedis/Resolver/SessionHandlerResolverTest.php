<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SessionRedis\Resolver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Yves\SessionRedis\Resolver\SessionHandlerResolver;
use Spryker\Yves\SessionRedisExtension\Dependency\Plugin\SessionRedisLockingExclusionConditionPluginInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SessionRedis
 * @group Resolver
 * @group SessionHandlerResolverTest
 * Add your own group annotations below this line
 */
class SessionHandlerResolverTest extends Unit
{
    /**
     * @var array<string, string>
     */
    protected array $serverBackup = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->serverBackup = $_SERVER;
        $_SERVER['REQUEST_URI'] = '/configurable-session-locking-test';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_HOST'] = 'suite-develop.local';
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->serverBackup;

        parent::tearDown();
    }

    public function testResolvesConditionTransferFromGlobalsWhenRequestStackHasNoCurrentRequest(): void
    {
        // Arrange
        $capturedConditionTransfer = null;

        $sessionRedisLockingExclusionConditionPluginMock = $this->getMockBuilder(SessionRedisLockingExclusionConditionPluginInterface::class)
            ->getMock();
        $sessionRedisLockingExclusionConditionPluginMock->method('checkCondition')
            ->willReturnCallback(function (RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer) use (&$capturedConditionTransfer): bool {
                $capturedConditionTransfer = $redisLockingSessionHandlerConditionTransfer;

                return false;
            });

        $sessionHandlerFactoryMock = $this->getMockBuilder(SessionHandlerFactoryInterface::class)
            ->getMock();
        $sessionHandlerFactoryMock->method('createSessionHandlerRedisLocking')
            ->willReturn($this->getMockBuilder(SessionHandlerInterface::class)->getMock());

        $sessionHandlerResolver = new SessionHandlerResolver(
            new RequestStack(),
            $sessionHandlerFactoryMock,
            [$sessionRedisLockingExclusionConditionPluginMock],
        );

        // Act
        $sessionHandlerResolver->resolveConfigurableRedisLockingSessionHandler(
            $this->getMockBuilder(SessionRedisWrapperInterface::class)->getMock(),
        );

        // Assert
        $this->assertInstanceOf(RedisLockingSessionHandlerConditionTransfer::class, $capturedConditionTransfer);
        $this->assertSame('/configurable-session-locking-test', $capturedConditionTransfer->getRequestUri());
        $this->assertSame('POST', $capturedConditionTransfer->getRequestMethod());
        $this->assertNotNull($capturedConditionTransfer->getRequestHeaders());
    }
}
