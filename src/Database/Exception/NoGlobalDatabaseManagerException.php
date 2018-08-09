<?php

namespace Nbj\Database\Exception;

use Exception;

class NoGlobalDatabaseManagerException extends Exception
{
    /**
     * InvalidConfigurationException constructor.
     */
    public function __construct()
    {
        $message = 'No global DatabaseManager has been set';

        parent::__construct($message, 500);
    }
}