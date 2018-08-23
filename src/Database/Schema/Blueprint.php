<?php

namespace Nbj\Database\Schema;

use Nbj\Database\Schema\Component\Index;
use Nbj\Database\Schema\Component\Column;
use Nbj\Database\Schema\Component\Constraint;

class Blueprint
{
    /**
     * Holds all columns added to the table blueprint
     *
     * @var array $columns
     */
    protected $columns = [];

    /**
     * Holds all indices added to the table blueprint
     *
     * @var array $indices
     */
    protected $indices = [];

    /**
     * Holds all constraints added to the table blueprint
     *
     * @var array $constraints
     */
    protected $constraints = [];

    /**
     * Gets all columns added to the blueprint
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Gets all indices added to the blueprint
     *
     * @return array
     */
    public function getIndices()
    {
        foreach ($this->columns as $column) {
            if ($column->hasIndex) {
                $this->addIndex([$column->name], false, null);
            }

            if ($column->isUnique) {
                $this->addIndex([$column->name], true, null);
            }
        }

        return $this->indices;
    }

    /**
     * Gets all the constraints added to the blueprint
     *
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Adds an integer column to the blueprint
     *
     * @param string $columnName
     * @param int $size
     *
     * @return Column
     */
    public function integer($columnName, $size = 11)
    {
        return $this->addColumn('integer', $columnName, $size);
    }

    /**
     * Adds a string column to the blueprint
     *
     * @param string $columnName
     * @param int $size
     *
     * @return Column
     */
    public function string($columnName, $size = 255)
    {
        return $this->addColumn('string', $columnName, $size);
    }

    /**
     * Adds a boolean column to the blueprint
     *
     * @param string $columnName
     *
     * @return Column
     */
    public function boolean($columnName)
    {
        return $this->addColumn('boolean', $columnName, 1);
    }

    /**
     * Adds a boolean column to the blueprint
     *
     * @param string $columnName
     *
     * @return Column
     */
    public function datetime($columnName)
    {
        return $this->addColumn('datetime', $columnName, null);
    }

    /**
     * Adds an index to the blueprint
     *
     * @param array $columns
     * @param string $name
     *
     * @return Blueprint
     */
    public function index(array $columns, $name = null)
    {
        return $this->addIndex($columns, false, $name);
    }

    /**
     * Adds a unique index to the blueprint
     *
     * @param array $columns
     * @param string $name
     *
     * @return Blueprint
     */
    public function unique(array $columns, $name = null)
    {
        return $this->addIndex($columns, true, $name);
    }

    /**
     * Adds a foreign constraint to the blueprint
     *
     * @param string $name
     *
     * @return Constraint
     */
    public function foreign($name)
    {
        return $this->addConstraint('foreign', $name);
    }

    /**
     * Adds a column to the blueprint
     *
     * @param string $type
     * @param string $name
     * @param int $size
     *
     * @return Column
     */
    public function addColumn($type, $name, $size)
    {
        $this->columns[$name] = new Column($type, $name, $size);

        return $this->columns[$name];
    }

    /**
     * Adds an index to the blueprint
     *
     * @param array $columns
     * @param bool $unique
     * @param string $name
     *
     * @return $this
     */
    public function addIndex(array $columns, $unique = false, $name = null)
    {
        $index = new Index($columns, $unique, $name);

        $this->indices[$index->name] = $index;

        return $this;
    }

    /**
     * Adds a constraint to the blueprint
     *
     * @param string $type
     * @param string $name
     *
     * @return Constraint
     */
    public function addConstraint($type, $name)
    {
        $this->constraints[$name] = new Constraint($type, $name);

        return $this->constraints[$name];
    }
}
