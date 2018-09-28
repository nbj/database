<?php

namespace Nbj\Database\QueryBuilder;

class WhereClause
{
    /**
     * Holds the column of the where clause
     *
     * @var string $column
     */
    public $column;

    /**
     * Holds the operator of the where clause
     *
     * @var string $operator
     */
    public $operator;

    /**
     * Holds the value of the where clause
     *
     * @var string $value
     */
    public $value;

    /**
     * Holds if the where clause is an or clause
     *
     * @var bool $isOr
     */
    public $isOr;

    /**
     * WhereClause constructor.
     *
     * @param string $column
     * @param string $operator
     * @param string $value
     */
    public function __construct($column, $operator, $value, $isOr = false)
    {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
        $this->isOr = $isOr;
    }
}
