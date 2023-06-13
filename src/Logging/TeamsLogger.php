<?php

namespace CbyteDigital\TeamsExceptionLogger\Logging;

use Monolog\Logger as MonologLogger;

class TeamsLogger extends MonologLogger
{
    /**
     * @param string $name
     */
    public function __construct($url, $level = MonologLogger::DEBUG, $name = 'Default')
    {
        parent::__construct('teams-logger');

        $this->pushHandler(new TeamsLoggerHandler($url, $level, $name));
    }
}
