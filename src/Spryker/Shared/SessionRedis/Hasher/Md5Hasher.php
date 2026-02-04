<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Hasher;

class Md5Hasher implements HasherInterface
{
    public function encrypt(string $string): string
    {
        return md5($string);
    }

    public function validate(string $string, string $hash): bool
    {
        return md5($string) === $hash;
    }
}
