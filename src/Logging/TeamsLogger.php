<?php

namespace CbyteDigital\TeamsLogger\Logging;

use CbyteDigital\TeamsLogger\Enums\LogType;
use Monolog\Logger as MonologLogger;

class TeamsLogger extends MonologLogger
{
    /**
     * @param string $name
     */
    public function __construct($url, $level = MonologLogger::DEBUG, $name = 'Default', $type = LogType::EXCEPTION)
    {
        parent::__construct('teams-logger');

        $this->pushHandler(new TeamsLoggerHandler($url, $level, $name, $type));
    }
}
