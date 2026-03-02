<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\KeyBuilder;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;

interface SessionKeyBuilderInterface
{
    public function buildSessionKey(string $sessionId): string;

    public function buildLockKey(string $sessionId): string;

    public function buildAccountKey(string $accountType, string $idAccount): string;

    public function buildEntityKey(SessionEntityRequestTransfer $sessionEntityRequestTransfer): string;
}
