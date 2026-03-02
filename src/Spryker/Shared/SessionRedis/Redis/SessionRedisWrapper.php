<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Redis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;

class SessionRedisWrapper implements SessionRedisWrapperInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface
     */
    protected $redisClient;

    /**
     * @var string
     */
    protected $connectionKey;

    public function __construct(
        SessionRedisToRedisClientInterface $redisClient,
        string $connectionKey,
        RedisConfigurationTransfer $configurationTransfer
    ) {
        $this->redisClient = $redisClient;
        $this->connectionKey = $connectionKey;

        $this->setupConnection($configurationTransfer);
    }

    public function get(string $key): ?string
    {
        return $this->redisClient->get($this->connectionKey, $key);
    }

    public function setex(string $key, int $seconds, string $value): bool
    {
        return $this->redisClient->setex($this->connectionKey, $key, $seconds, $value);
    }

    public function set(string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool
    {
        return $this->redisClient->set($this->connectionKey, $key, $value, $expireResolution, $expireTTL, $flag);
    }

    public function del(array $keys): int
    {
        return $this->redisClient->del($this->connectionKey, $keys);
    }

    /**
     * @param string $script
     * @param int $numKeys
     * @param array $keysOrArgs
     *
     * @return bool
     */
    public function eval(string $script, int $numKeys, ...$keysOrArgs): bool
    {
        return $this->redisClient->eval($this->connectionKey, $script, $numKeys, ...$keysOrArgs);
    }

    public function connect(): void
    {
        $this->redisClient->connect($this->connectionKey);
    }

    public function disconnect(): void
    {
        $this->redisClient->disconnect($this->connectionKey);
    }

    public function isConnected(): bool
    {
        return $this->redisClient->isConnected($this->connectionKey);
    }

    public function mget(array $keys): array
    {
        return $this->redisClient->mget($this->connectionKey, $keys);
    }

    public function mset(array $dictionary): bool
    {
        return $this->redisClient->mset($this->connectionKey, $dictionary);
    }

    public function info(?string $section = null): array
    {
        return $this->redisClient->info($this->connectionKey, $section);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $pattern
     *
     * @return array
     */
    public function keys(string $pattern): array
    {
        return $this->redisClient->keys($this->connectionKey, $pattern);
    }

    protected function setupConnection(RedisConfigurationTransfer $configurationTransfer): void
    {
        $this->redisClient->setupConnection(
            $this->connectionKey,
            $configurationTransfer,
        );
    }
}
