<?php

namespace Nbj\Database\Exception;

use Exception;

class GrammarDoesNotExist extends Exception
{
    /**
     * GrammarDoesNotExistException constructor.
     *
     * @param $connection
     */
    public function __construct($connection)
    {
        $message = sprintf('Grammar for connection type: %s was not found', $connection);

        parent::__construct($message, 500);
    }
}
