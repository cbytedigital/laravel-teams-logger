<?php

namespace CbyteDigital\TeamsLogger\Enums;

use Illuminate\Validation\Rules\Enum;

/**
 * @method static static STRING()
 * @method static static EXCEPTION()
 */
final class LogType extends Enum
{
    const STRING = 'Simple';
    const EXCEPTION = 'Exception';
}
