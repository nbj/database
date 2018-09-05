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

    /**
     * Holds translations for data types
     *
     * @var array $typeTranslations
     */
    protected $typeTranslations = [
        'integer'  => 'INTEGER',
        'string'   => 'VARCHAR',
        'text'     => 'TEXT',
        'datetime' => 'DATETIME',
        'boolean'  => 'BOOLEAN',
    ];

    /**
     * Sqlite constructor.
     *
     * @param QueryBuilder $builder
     */
    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }
}
