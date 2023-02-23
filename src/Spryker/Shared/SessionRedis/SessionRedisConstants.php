<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SessionRedisConstants
{
    /**
     * Specification:
     * - Defines a ttl value for Yves session data.
     *
     * @api
     *
     * @var string
     */
    public const YVES_SESSION_TIME_TO_LIVE = 'SESSION_REDIS:YVES_SESSION_TIME_TO_LIVE';

    /**
     * Specification:
     * - Defines an array of DSN strings (describing multiple instances, sentinels) to be used for Redis replication/cluster mode.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     *
     * @api
     *
     * @var string
     */
    public const YVES_SESSION_REDIS_DATA_SOURCE_NAMES = 'SESSION_REDIS:YVES_SESSION_REDIS_DATA_SOURCE_NAMES';

    /**
     * Specification:
     * - Defines custom client options for Redis when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const YVES_SESSION_REDIS_CLIENT_OPTIONS = 'SESSION_REDIS:YVES_SESSION_PREDIS_CLIENT_OPTIONS';

    /**
     * Specification:
     * - Defines a protocol (tcp:// or redis://) for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::YVES_SESSION_REDIS_SCHEME} instead.
     *
     * @var string
     */
    public const YVES_SESSION_REDIS_PROTOCOL = 'SESSION_REDIS:YVES_SESSION_REDIS_PROTOCOL';

    /**
     * Specification:
     * - Defines a scheme|protocol (tcp:// or redis://) for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const YVES_SESSION_REDIS_SCHEME = 'SESSION_REDIS:YVES_SESSION_REDIS_SCHEME';

    /**
     * Specification:
     * - Defines a password for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const YVES_SESSION_REDIS_PASSWORD = 'SESSION_REDIS:YVES_SESSION_REDIS_PASSWORD';

    /**
     * Specification:
     * - Defines a host for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const YVES_SESSION_REDIS_HOST = 'SESSION_REDIS:YVES_SESSION_REDIS_HOST';

    /**
     * Specification:
     * - Defines a port for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const YVES_SESSION_REDIS_PORT = 'SESSION_REDIS:YVES_SESSION_REDIS_PORT';

    /**
     * Specification:
     * - Defines a database for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const YVES_SESSION_REDIS_DATABASE = 'SESSION_REDIS:YVES_SESSION_REDIS_DATABASE';

    /**
     * Specification:
     * - Defines a ttl value for Zed session data.
     *
     * @api
     *
     * @var string
     */
    public const ZED_SESSION_TIME_TO_LIVE = 'SESSION_REDIS:ZED_SESSION_TIME_TO_LIVE';

    /**
     * Specification:
     * - Defines an array of DSN strings (describing multiple instances, sentinels) to be used for Redis replication/cluster mode.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     *
     * @api
     *
     * @var string
     */
    public const ZED_SESSION_REDIS_DATA_SOURCE_NAMES = 'SESSION_REDIS:ZED_SESSION_REDIS_DATA_SOURCE_NAMES';

    /**
     * Specification:
     * - Defines custom options for `\Predis\Client` when used as Zed session storage.
     *
     * @api
     *
     * @var string
     */
    public const ZED_SESSION_REDIS_CLIENT_OPTIONS = 'SESSION_REDIS:ZED_SESSION_PREDIS_CLIENT_OPTIONS';

    /**
     * Specification:
     * - Defines a protocol (tcp:// or redis://) for Redis connection when used as Zed session storage.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::ZED_SESSION_REDIS_SCHEME} instead.
     *
     * @var string
     */
    public const ZED_SESSION_REDIS_PROTOCOL = 'SESSION_REDIS:ZED_SESSION_REDIS_PROTOCOL';

    /**
     * Specification:
     * - Defines a scheme|protocol (tcp:// or redis://) for Redis connection when used as Zed session storage.
     *
     * @api
     *
     * @var string
     */
    public const ZED_SESSION_REDIS_SCHEME = 'SESSION_REDIS:ZED_SESSION_REDIS_SCHEME';

    /**
     * Specification:
     * - Defines a host for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const ZED_SESSION_REDIS_HOST = 'SESSION_REDIS:ZED_SESSION_REDIS_HOST';

    /**
     * Specification:
     * - Defines a port for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const ZED_SESSION_REDIS_PORT = 'SESSION_REDIS:ZED_SESSION_REDIS_PORT';

    /**
     * Specification:
     * - Defines a password for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const ZED_SESSION_REDIS_PASSWORD = 'SESSION_REDIS:ZED_SESSION_REDIS_PASSWORD';

    /**
     * Specification:
     * - Defines a database for Redis connection when used as Yves session storage.
     *
     * @api
     *
     * @var string
     */
    public const ZED_SESSION_REDIS_DATABASE = 'SESSION_REDIS:ZED_SESSION_REDIS_DATABASE';

    /**
     * Specification:
     * - Sets the session locking timeout in milliseconds.
     *
     * @api
     *
     * @var string
     */
    public const LOCKING_TIMEOUT_MILLISECONDS = 'SESSION_REDIS:LOCKING_TIMEOUT_MILLISECONDS';

    /**
     * Specification:
     * - Sets the delay between attempts to acquire the lock in microseconds.
     *
     * @api
     *
     * @var string
     */
    public const LOCKING_RETRY_DELAY_MICROSECONDS = 'SESSION_REDIS:LOCKING_RETRY_DELAY_MICROSECONDS';

    /**
     * Specification:
     * - Sets the time to live for a lock in milliseconds.
     *
     * @api
     *
     * @var string
     */
    public const LOCKING_LOCK_TTL_MILLISECONDS = 'SESSION_REDIS:LOCKING_LOCK_TTL_MILLISECONDS';
}
