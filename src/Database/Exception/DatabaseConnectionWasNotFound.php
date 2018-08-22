<?php

namespace Nbj\Database\Exception;

use Exception;

class DatabaseConnectionWasNotFound extends Exception
{
    /**
     * DatabaseConnectionWasNotFoundException constructor.
     *
     * @param string $connection
     */
    public function __construct($connection)
    {
        $message = sprintf('DatabaseConnection: %s was not found.', $connection);

        parent::__construct($message, 500);
    }
}
