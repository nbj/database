<?php

namespace Nbj\Database\Grammar;

use Nbj\Database\QueryBuilder\Builder;

class Sqlite extends Grammar
{
    /**
     * Holds the instance of the query builder building the query
     *
     * @var Builder
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
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }
}
