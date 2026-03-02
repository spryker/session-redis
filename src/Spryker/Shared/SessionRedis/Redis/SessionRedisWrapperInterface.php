<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Redis;

interface SessionRedisWrapperInterface
{
    public function get(string $key): ?string;

    public function setex(string $key, int $seconds, string $value): bool;

    public function set(string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool;

    public function del(array $keys): int;

    /**
     * @param string $script
     * @param int $numKeys
     * @param array $keysOrArgs
     *
     * @return bool
     */
    public function eval(string $script, int $numKeys, ...$keysOrArgs): bool;

    public function connect(): void;

    public function disconnect(): void;

    public function isConnected(): bool;

    public function mget(array $keys): array;

    public function mset(array $dictionary): bool;

    public function info(?string $section = null): array;

    /**
     * @deprecated Will be removed with next major release.
     *
     * @param string $pattern
     *
     * @return array
     */
    public function keys(string $pattern);
}
