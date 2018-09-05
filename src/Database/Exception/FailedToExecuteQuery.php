<?php

namespace Nbj\Database\Exception;

use Exception;
use PDOStatement;

class FailedToExecuteQuery extends Exception
{
    /**
     * FailedToExecuteQuery constructor.
     *
     * @param PDOStatement $statement
     */
    public function __construct(PDOStatement $statement)
    {
        $message = sprintf('Failed to execute query: %s', $statement->queryString);

        parent::__construct($message, 500);
    }
}
