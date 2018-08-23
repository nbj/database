<?php

namespace Nbj\Database\Schema\Component;

class Constraint
{
    /**
     * Holds the name of the constraint
     *
     * @var string $name
     */
    public $name;

    /**
     * Holds the type of constraint
     *
     * @var string $type
     */
    public $type;

    /**
     * Holds the column name which is referenced
     *
     * @var string $references
     */
    public $references;
    /**
     * Holds the table which is referenced
     *
     * @var string $on
     */
    public $on;

    /**
     * Constraint constructor.
     *
     * @param string $type
     * @param string $name
     */
    public function __construct($type, $name)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Sets which column the constraint references
     *
     * @param string $columnName
     *
     * @return $this
     */
    public function references($columnName)
    {
        $this->references = $columnName;

        return $this;
    }

    /**
     * Sets which table the constraint references
     *
     * @param string $tableName
     *
     * @return $this
     */
    public function on($tableName)
    {
        $this->on = $tableName;

        return $this;
    }
}
