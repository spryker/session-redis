<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SessionRedis\Communication;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\SessionRedisConstants;
use SprykerTest\Zed\SessionRedis\SessionRedisCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SessionRedis
 * @group Communication
 * @group SessionRedisConfigTest
 * Add your own group annotations below this line
 */
class SessionRedisConfigTest extends Unit
{
    protected SessionRedisCommunicationTester $tester;

    public function testGetZedRedisConnectionConfigurationSetsPersistentConnectionWhenEnabled(): void
    {
        $this->mockRequiredZedConnectionConfig();
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::ZED_SESSION_PERSISTENT_CONNECTION, true);

        $isPersistent = $this->tester->getModuleConfig()
            ->getZedRedisConnectionConfiguration()
            ->getConnectionCredentials()
            ->getIsPersistent();

        $this->assertTrue($isPersistent);
    }

    public function testGetZedRedisConnectionConfigurationSetsPersistentConnectionFalseWhenDisabled(): void
    {
        $this->mockRequiredZedConnectionConfig();
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::ZED_SESSION_PERSISTENT_CONNECTION, false);

        $isPersistent = $this->tester->getModuleConfig()
            ->getZedRedisConnectionConfiguration()
            ->getConnectionCredentials()
            ->getIsPersistent();

        $this->assertFalse($isPersistent);
    }

    public function testGetYvesRedisConnectionConfigurationSetsPersistentConnectionWhenEnabled(): void
    {
        $this->mockRequiredYvesConnectionConfig();
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_PERSISTENT_CONNECTION, true);

        $isPersistent = $this->tester->getModuleConfig()
            ->getYvesRedisConnectionConfiguration()
            ->getConnectionCredentials()
            ->getIsPersistent();

        $this->assertTrue($isPersistent);
    }

    public function testGetYvesRedisConnectionConfigurationSetsPersistentConnectionFalseWhenDisabled(): void
    {
        $this->mockRequiredYvesConnectionConfig();
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_PERSISTENT_CONNECTION, false);

        $isPersistent = $this->tester->getModuleConfig()
            ->getYvesRedisConnectionConfiguration()
            ->getConnectionCredentials()
            ->getIsPersistent();

        $this->assertFalse($isPersistent);
    }

    protected function mockRequiredZedConnectionConfig(): void
    {
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::ZED_SESSION_REDIS_HOST, 'localhost');
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::ZED_SESSION_REDIS_PORT, 6379);
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::ZED_SESSION_REDIS_DATABASE, 0);
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::ZED_SESSION_REDIS_PROTOCOL, 'tcp');
    }

    protected function mockRequiredYvesConnectionConfig(): void
    {
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_REDIS_HOST, 'localhost');
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_REDIS_PORT, 6379);
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_REDIS_DATABASE, 0);
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_REDIS_PROTOCOL, 'tcp');
    }
}
