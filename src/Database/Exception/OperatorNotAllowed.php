<?php

namespace Nbj\Database\Exception;

use Exception;

class OperatorNotAllowed extends Exception
{
    /**
     * OperatorNotAllowed constructor.
     *
     * @param string $operator
     */
    public function __construct($operator)
    {
        $message = sprintf('Operator: %s is not a valid operator', $operator);

        parent::__construct($message, 500);
    }
}
