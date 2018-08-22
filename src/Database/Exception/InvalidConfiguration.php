<?php

namespace Nbj\Database\Exception;

use Exception;

class InvalidConfiguration extends Exception
{
    /**
     * InvalidConfigurationException constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, 500);
    }
}
