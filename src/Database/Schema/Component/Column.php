<?php

namespace Nbj\Database\Schema\Component;

class Column
{
    /**
     * Holds the name of the column
     *
     * @var string $name
     */
    public $name;

    /**
     * Holds the type of the column
     *
     * @var string $type
     */
    public $type;

    /**
     * Holds the size of the column
     *
     * @var int $size
     */
    public $size;

    /**
     * Tells whether the column is the primary key
     *
     * @var bool $isPrimary
     */
    public $isPrimary = false;

    /**
     * Tells whether the column is uniquely indexed
     *
     * @var bool $isUnique
     */
    public $isUnique = false;

    /**
     * Tells whether the column is nullable
     *
     * @var bool $isNullable
     */
    public $isNullable = false;

    /**
     * Tells whether the column auto increments
     *
     * @var bool $autoIncrements
     */
    public $autoIncrements = false;

    /**
     * Tells whether the column is unsigned when using integer or double
     *
     * @var bool $isUnsigned
     */
    public $isUnsigned = false;

    /**
     * Tells if the column has an index
     *
     * @var bool $hasIndex
     */
    public $hasIndex = false;

    /**
     * Tells the default value of the column
     *
     * @var bool $defaultValue
     */
    public $defaultValue = null;

    /**
     * Column constructor.
     *
     * @param string$type
     * @param string $name
     * @param int $size
     */
    public function __construct($type, $name, $size)
    {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
    }

    /**
     * Sets the column as the primary key
     *
     * @return $this
     */
    public function primary()
    {
        $this->isPrimary = true;

        return $this;
    }

    /**
     * Sets the column as auto incrementing
     *
     * @return $this
     */
    public function autoIncrement()
    {
        $this->autoIncrements = true;

        return $this;
    }

    /**
     * Sets the column as nullable
     *
     * @return $this
     */
    public function nullable()
    {
        $this->isNullable = true;

        return $this;
    }

    /**
     * Sets the column to be unsigned
     *
     * @return $this
     */
    public function unsigned()
    {
        $this->isUnsigned = true;

        return $this;
    }

    /**
     * Sets the column to have an index
     *
     * @return $this
     */
    public function index()
    {
        $this->hasIndex = true;

        return $this;
    }

    /**
     * Sets the column to have an unique index
     *
     * @return $this
     */
    public function unique()
    {
        $this->isUnique = true;

        return $this;
    }

    /**
     * Sets the default value for the column
     *
     * @param string $value
     *
     * @return $this
     */
    public function default($value)
    {
        $this->defaultValue = $value;

        return $this;
    }
}
