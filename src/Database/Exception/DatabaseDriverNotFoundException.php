<?php

namespace Nbj\Database\Exception;

use Exception;

class DatabaseDriverNotFoundException extends Exception
{
    /**
     * InvalidConfigurationException constructor.
     *
     * @param string $driver
     */
    public function __construct($driver)
    {
        $message = sprintf('Database driver: %s was not found.', $driver);

        parent::__construct($message, 500);
    }
}
