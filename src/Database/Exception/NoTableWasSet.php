<?php

namespace Nbj\Database\Exception;

use Exception;

class NoTableWasSet extends Exception
{
    /**
     * NoTableWasSet constructor.
     */
    public function __construct()
    {
        $message = 'No table was set for QueryBuilder';

        parent::__construct($message, 500);
    }
}
