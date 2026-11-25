<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Handler;

use Codeception\Test\Unit;
use SessionUpdateTimestampHandlerInterface;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculator;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionSpinLockLocker;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisWriteOnlyLocking;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Handler
 * @group SessionHandlerRedisWriteOnlyLockingTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisWriteOnlyLockingTest extends Unit
{
    protected const int TIME_TO_LIVE = 60;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisLocking
     */
    protected $sessionHandler;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Handler\Lock\SessionSpinLockLocker
     */
    protected $spinLockLockerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculator
     */
    protected $sessionRedisLifeTimeCalculatorMock;

    public function testReadShouldNotLockSession(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('session:testSessionId'))
            ->willReturn('testValue');

        $this->spinLockLockerMock
            ->expects($this->never())
            ->method('lock');

        $this->assertEquals(
            'testValue',
            $this->sessionHandler->read('testSessionId'),
        );
    }

    public function testValidateIdDoesNotLockSession(): void
    {
        $this->redisClientMock
            ->method('get')
            ->with('session:testSessionId')
            ->willReturn(null);

        $this->spinLockLockerMock
            ->expects($this->never())
            ->method('lock');

        $this->assertInstanceOf(SessionUpdateTimestampHandlerInterface::class, $this->sessionHandler);
        $this->assertFalse($this->sessionHandler->validateId('testSessionId'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupRedisClientMock();
        $this->setupRedisSpinLockLockerMock();
        $this->setupSessionRedisLifeTimeCalculatorMock();
        $this->setupSessionHandlerRedisWriteOnlyLocking();
    }

    protected function setupRedisClientMock(): void
    {
        $this->redisClientMock = $this
            ->getMockBuilder(SessionRedisWrapperInterface::class)
            ->getMock();
    }

    protected function setupSessionHandlerRedisWriteOnlyLocking(): void
    {
        $this->sessionHandler = new SessionHandlerRedisWriteOnlyLocking(
            $this->redisClientMock,
            $this->spinLockLockerMock,
            new SessionKeyBuilder(),
            $this->sessionRedisLifeTimeCalculatorMock,
            [],
        );
    }

    protected function setupRedisSpinLockLockerMock(): void
    {
        $this->spinLockLockerMock = $this
            ->getMockBuilder(SessionSpinLockLocker::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function setupSessionRedisLifeTimeCalculatorMock(): void
    {
        $this->sessionRedisLifeTimeCalculatorMock = $this->getMockBuilder(SessionRedisLifeTimeCalculator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionRedisLifeTimeCalculatorMock->method('getSessionLifeTime')
            ->willReturn(static::TIME_TO_LIVE);
    }
}
