<?php

namespace CbyteDigital\TeamsLogger\Logging;

use CbyteDigital\TeamsLogger\Enums\LogType;
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
            $config['name'] ?? null,
            $config['type'] ?? LogType::STRING
        );
    }
}
