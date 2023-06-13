<?php

namespace CbyteDigital\TeamsLogger\Logging;

use App\Models\User;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class TeamsLoggerHandler extends AbstractProcessingHandler
{
    /** @var string */
    private $url;

    /** @var string */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($url, $level = Logger::DEBUG, $name = 'Default')
    {
        parent::__construct($level);

        $this->url   = $url;
        $this->name  = $name;
    }

    /**
     * @param array $record
     *
     * @return array|void
     */
    protected function getMessage(array $record)
    {
        if (!isset($record['context']['exception'])) {
            return;
        }

        $stacktrace = '';

        $whitespace = [
                'name' => ' ',
                'value' => ' ',
            ];

        foreach ($record['context']['exception']->getTrace() as $trace) {
            if (isset($trace['file'])) {
                $stacktrace .= $trace['file'] . '<br>';
            }
        }

        $message = new TeamsLoggerMessage([
            "summary" => "Error 🚨",
            "title" => "Error 🚨",
            'sections' => [
                [
                    'activityTitle'    => 'Info',
                    'activitySubtitle' => '',
                    'facts'            => [
                        [
                            'name' => 'Request',
                            'value' => '<a href="' . request()->getSchemeAndHttpHost() .
                                request()->getRequestUri() . '">'.request()->getRequestUri().'</a>',
                        ],
                        [
                            'name' => 'Type:',
                            'value' => request()->getMethod(),
                        ],
                        [
                            'name' => 'User:',
                            'value' => request()->user() instanceof User ? request()->user()->email : 'Unknown',
                        ],
                        (
                            defined ('LARAVEL_START') ?
                            [
                                'name' => 'Execution time:',
                                'value' => floor((microtime(true) - LARAVEL_START) * 1000) . 'ms',
                            ]
                            : []
                        ),
                        $whitespace,
                        $whitespace,
                        $whitespace,
                        $whitespace,
                        [
                            'name' => 'Exception',
                            'value' => $record['message'],
                        ],
                        [
                            'name' => 'File:',
                            'value' => $record['context']['exception']->getFile(),
                        ],
                        [
                            'name' => 'Line:',
                            'value' => $record['context']['exception']->getLine(),
                        ],
                        [
                            'name' => 'Stacktrace:',
                            'value' => $stacktrace,
                        ]
                    ],
                    'markdown'         => true
                ],
            ],
            'themeColor' => '721C24',
        ]);

        return $message->jsonSerialize();
    }

    /**
     * @param array $record
     */
    protected function write($record): void
    {
        $json = null;

        if ($record instanceof LogRecord) {
            $json = json_encode($this->getMessage($record->toArray()));
        } else {
            $json = json_encode($this->getMessage($record));
        }


        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ]);

        curl_exec($ch);
    }
}
