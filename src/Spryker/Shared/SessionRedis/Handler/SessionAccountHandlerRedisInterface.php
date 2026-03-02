<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

interface SessionAccountHandlerRedisInterface
{
    public function saveSessionAccount(int $idAccount, string $idSession): void;

    public function isSessionAccountValid(int $idAccount, string $idSession): bool;
}
