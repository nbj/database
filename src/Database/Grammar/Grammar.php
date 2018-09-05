<?php

namespace Nbj\Database\Grammar;

use Nbj\Database\Schema\Blueprint;
use Nbj\Database\Schema\Component\Column;

abstract class Grammar
{
    /**
     * Holds translations for data types
     *
     * @var array $typeTranslations
     */
    protected $typeTranslations = [];

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

            if ($column->defaultValue) {
                $columnSql = sprintf('%s DEFAULT(%s)', $columnSql, $column->defaultValue);
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
     *
     * @return string
     */
    public function compileSelect($table, array $columns)
    {
        $columns = implode(', ', $columns);

        return sprintf('SELECT %s FROM %s', $columns, $table);
    }
}
