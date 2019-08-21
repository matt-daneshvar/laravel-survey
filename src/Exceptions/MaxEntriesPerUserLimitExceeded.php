<?php

namespace MattDaneshvar\Survey\Exceptions;

use Exception;

class MaxEntriesPerUserLimitExceeded extends Exception
{
    /**
     * The exception message.
     *
     * @var string
     */
    protected $message = 'Maximum entries per user exceeded.';
}
