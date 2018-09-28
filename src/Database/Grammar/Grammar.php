<?php

namespace Nbj\Database\Grammar;

use Nbj\Database\Schema\Blueprint;
use Nbj\Database\Schema\Component\Column;
use Nbj\Database\QueryBuilder\WhereClause;

abstract class Grammar
{
    /**
     * Holds translations for data types
     *
     * @var array $typeTranslations
     */
    protected $typeTranslations = [];

    /**
     * Compiles a create table query
     *
     * @param Blueprint $blueprint
     *
     * @return string
     */
    public function compileCreateTable(Blueprint $blueprint)
    {
        $columns = implode(",\n", array_map(function (Column $column) {
            $dataType = $this->typeTranslations[$column->type];

            $columnSql = sprintf("\t%s %s", $column->name, $dataType);

            if ($column->size) {
                $columnSql = sprintf('%s (%s)', $columnSql, $column->size);
            }

            if (!$column->isNullable && !$column->isPrimary) {
                $columnSql = sprintf('%s NOT NULL', $columnSql);
            }

            if ($column->isPrimary) {
                $columnSql = sprintf('%s PRIMARY KEY', $columnSql);
            }

            if ($column->autoIncrements) {
                $columnSql = sprintf('%s AUTOINCREMENT', $columnSql);
            }

            if ($column->hasDefaultValue) {
                $value = $column->defaultValue === false ? 0 : $column->defaultValue;
                $value = $column->defaultValue === true ? 1 : $value;
                $value = $column->defaultValue === null ? 'NULL' : $value;

                $columnSql = sprintf('%s DEFAULT(%s)', $columnSql, $value);
            }

            return $columnSql;
        }, $blueprint->getColumns()));

        return sprintf("CREATE TABLE %s (\n%s\n);", $blueprint->getTable(), $columns);
    }

    /**
     * Compiles a select query
     *
     * @param string $table
     * @param array $columns
     * @param array $whereClauses
     *
     * @return string
     */
    public function compileSelect($table, array $columns, array $whereClauses = [])
    {
        $columns = implode(', ', $columns);
        $wheres = $this->compileWheres($whereClauses);

        return sprintf('SELECT %s FROM %s%s', $columns, $table, $wheres);
    }

    /**
     * Compiles a where clause
     *
     * @param WhereClause $where
     *
     * @return string
     */
    public function compileWhere(WhereClause $where)
    {
        $value = sprintf("'%s'", $where->value);

        if (is_numeric($where->value)) {
            $value = $where->value;
        }

        return sprintf('%s %s %s', $where->column, $where->operator, $value);
    }

    /**
     * Compiles all where clauses
     *
     * @param array $whereClauses
     *
     * @return string
     */
    protected function compileWheres(array $whereClauses)
    {
        $wheres = '';
        $isFirstWhere = true;
        $isOr = false;
        $whereClause = array_shift($whereClauses);

        while ($whereClause) {
            $compiledWhereClause = $this->compileWhere($whereClause);
            $whereClause = array_shift($whereClauses);

            while ($whereClause && $whereClause->isOr) {
                $isOr = true;
                $compiledWhereClause = sprintf("%s OR %s", $compiledWhereClause, $this->compileWhere($whereClause));
                $whereClause = array_shift($whereClauses);
            }

            if ($isOr) {
                $isOr = false;
                $compiledWhereClause = sprintf("(%s)", $compiledWhereClause);
            }

            if ($isFirstWhere) {
                $wheres = sprintf(" WHERE %s", $compiledWhereClause);
                $isFirstWhere = false;

                continue;
            }

            $wheres = sprintf("%s AND %s", $wheres, $compiledWhereClause);
        }

        return $wheres;
    }
}
