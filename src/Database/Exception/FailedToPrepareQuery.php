<?php

namespace Nbj\Database\Exception;

use Exception;

class FailedToPrepareQuery extends Exception
{
    /**
     * FailedToPrepareQuery constructor.
     *
     * @param string $sql
     */
    public function __construct($sql)
    {
        $message = sprintf('Failed to execute query: %s', $sql);

        parent::__construct($message, 500);
    }
}
