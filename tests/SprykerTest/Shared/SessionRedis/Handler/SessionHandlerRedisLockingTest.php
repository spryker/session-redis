<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Handler;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Handler\Exception\LockCouldNotBeAcquiredException;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculator;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionSpinLockLocker;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Handler
 * @group SessionHandlerRedisLockingTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisLockingTest extends Unit
{
    /**
     * @var int
     */
    protected const TIME_TO_LIVE = 60;

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

    public function testReadReturnsEmptyStringOnMissingSessionKey(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('session:session_key'))
            ->willReturn(null);

        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('lock')
            ->willReturn(true);

        $sessionData = $this->sessionHandler->read('session_key');

        $this->assertSame('', $sessionData);
    }

    public function testGcReturnsTrue(): void
    {
        $this->assertTrue($this->sessionHandler->gc(1));
    }

    public function testReadingSessionDataWillThrowExceptionWhenImpossibleToAcquireLock(): void
    {
        $this->expectException(LockCouldNotBeAcquiredException::class);
        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('lock')
            ->willReturn(false);

        $this->sessionHandler->read('session_key');
    }

    public function testCanDestroySession(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('del')
            ->with(['session:session_key'])
            ->willReturn(1);

        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('unlockCurrent');

        $this->assertTrue($this->sessionHandler->destroy('session_key'));
    }

    public function testDestructorUnlocksSessionDataAndDisconnectsFromRedis(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('disconnect');

        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('unlockCurrent');

        unset($this->sessionHandler);
    }

    public function testClosingSessionUnlocksSessionData(): void
    {
        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('unlockCurrent');

        $this->assertTrue($this->sessionHandler->close());
    }

    public function testWritesSessionDataWithTtlSet(): void
    {
        $dummyData = 'foo bar baz';
        $this->redisClientMock
            ->expects($this->once())
            ->method('setex')
            ->with(
                $this->equalTo('session:session_key'),
                $this->equalTo(static::TIME_TO_LIVE),
                $this->equalTo($dummyData),
            )
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->write('session_key', $dummyData));
    }

    public function testReadDecodesLegacyJsonSession(): void
    {
        $dummyData = 'foo bar baz';
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('session:session_key'))
            ->willReturn(json_encode($dummyData));

        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('lock')
            ->willReturn(true);

        $sessionData = $this->sessionHandler->read('session_key');

        $this->assertSame($dummyData, $sessionData);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupRedisClientMock();
        $this->setupRedisSpinLockLockerMock();
        $this->setupSessionRedisLifeTimeCalculatorMock();
        $this->setupSessionHandlerRedisLocking();
    }

    protected function setupRedisClientMock(): void
    {
        $this->redisClientMock = $this
            ->getMockBuilder(SessionRedisWrapperInterface::class)
            ->getMock();
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

    protected function setupSessionHandlerRedisLocking(): void
    {
        $this->sessionHandler = new SessionHandlerRedisLocking(
            $this->redisClientMock,
            $this->spinLockLockerMock,
            new SessionKeyBuilder(),
            $this->sessionRedisLifeTimeCalculatorMock,
        );
    }
}
