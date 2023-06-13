<?php

namespace CbyteDigital\TeamsExceptionLogger\Logging;

use Monolog\Logger as MonologLogger;

class TeamsLoggingChannel
{
    /**
     * @param array $config
     *
     * @return MonologLogger
     */
    public function __invoke(array $config)
    {
        return new TeamsLogger(
            $config['url'],
            $config['level'] ?? MonologLogger::DEBUG,
            $config['name'] ?? null
        );
    }
}
