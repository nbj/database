<?php

namespace Nbj\Database\Exception;

use Exception;

class NoGlobalDatabaseManager extends Exception
{
    /**
     * NoGlobalDatabaseManagerException constructor.
     */
    public function __construct()
    {
        $message = 'No global DatabaseManager has been set';

        parent::__construct($message, 500);
    }
}
