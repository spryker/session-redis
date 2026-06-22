<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SessionRedis;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\SessionRedisConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SessionRedis
 * @group SessionRedisConfigTest
 * Add your own group annotations below this line
 */
class SessionRedisConfigTest extends Unit
{
    protected SessionRedisYvesTester $tester;

    public function testGetRedisConnectionConfigurationSetsPersistentConnectionWhenEnabled(): void
    {
        $this->mockRequiredConnectionConfig();
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_PERSISTENT_CONNECTION, true);

        $isPersistent = $this->tester->getModuleConfig()
            ->getRedisConnectionConfiguration()
            ->getConnectionCredentials()
            ->getIsPersistent();

        $this->assertTrue($isPersistent);
    }

    public function testGetRedisConnectionConfigurationSetsPersistentConnectionFalseWhenDisabled(): void
    {
        $this->mockRequiredConnectionConfig();
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_PERSISTENT_CONNECTION, false);

        $isPersistent = $this->tester->getModuleConfig()
            ->getRedisConnectionConfiguration()
            ->getConnectionCredentials()
            ->getIsPersistent();

        $this->assertFalse($isPersistent);
    }

    protected function mockRequiredConnectionConfig(): void
    {
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_REDIS_HOST, 'localhost');
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_REDIS_PORT, 6379);
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_REDIS_DATABASE, 0);
        $this->tester->mockEnvironmentConfig(SessionRedisConstants::YVES_SESSION_REDIS_PROTOCOL, 'tcp');
    }
}
