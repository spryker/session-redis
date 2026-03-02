<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Handler;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculator;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedis;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Handler
 * @group SessionHandlerRedisTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisTest extends Unit
{
    /**
     * @var int
     */
    protected const SESSION_LIFETIME = 60;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\SessionHandlerRedis
     */
    protected $sessionHandler;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    protected $monitoringServiceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculator
     */
    protected $sessionRedisLifeTimeCalculatorMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupRedisClientMock();
        $this->setupMonitoringServiceMock();
        $this->setupSessionRedisLifeTimeCalculatorMock();
        $this->setupSessionHandler();
    }

    public function canConnectToRedisWhenSessionIsOpen(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('connect');

        $this->assertTrue($this->sessionHandler->open('save path', 'session name'));
    }

    public function testCanDisconnectFromRedisWhenSessionIsClosed(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('disconnect');

        $this->assertTrue($this->sessionHandler->close());
    }

    public function testCanReadEmptyData(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $this->assertSame('', $this->sessionHandler->read('save id'));
    }

    public function testCanReadNonEmptyData(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->willReturn('"data"');

        $this->assertSame('data', $this->sessionHandler->read('save id'));
    }

    public function testGcReturnsTrue(): void
    {
        $this->assertTrue($this->sessionHandler->gc(1));
    }

    public function testCatDestroySession(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('del')
            ->with(['session:session_id']);

        $this->sessionHandler->destroy('session_id');
    }

    public function testWriterReturnsTrueWhenDataIsEmpty(): void
    {
        $this->assertTrue($this->sessionHandler->write('session_id', ''));
    }

    public function testCanWriteExpirableData(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('setex')
            ->with(
                'session:session_id',
                static::SESSION_LIFETIME,
                json_encode('data'),
            )
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->write('session_id', 'data'));
    }

    protected function setupRedisClientMock(): void
    {
        $this->redisClientMock = $this->getMockBuilder(SessionRedisWrapperInterface::class)
            ->getMock();
    }

    protected function setupMonitoringServiceMock(): void
    {
        $this->monitoringServiceMock = $this->getMockBuilder(SessionRedisToMonitoringServiceInterface::class)
            ->getMock();
    }

    protected function setupSessionRedisLifeTimeCalculatorMock(): void
    {
        $this->sessionRedisLifeTimeCalculatorMock = $this->getMockBuilder(SessionRedisLifeTimeCalculator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionRedisLifeTimeCalculatorMock->method('getSessionLifeTime')
            ->willReturn(static::SESSION_LIFETIME);
    }

    protected function setupSessionHandler(): void
    {
        $this->sessionHandler = new SessionHandlerRedis(
            $this->redisClientMock,
            new SessionKeyBuilder(),
            $this->monitoringServiceMock,
            $this->sessionRedisLifeTimeCalculatorMock,
        );
    }
}
