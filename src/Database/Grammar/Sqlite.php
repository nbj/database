<?php

namespace Nbj\Database\Grammar;

use Nbj\Database\QueryBuilder;

class Sqlite extends Grammar
{
    /**
     * Holds the instance of the query builder building the query
     *
     * @var QueryBuilder
     */
    protected $builder;

    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }
}
