<?php

namespace Nbj\Database\Schema\Component;

class Index
{
    /**
     * Holds the name of the index
     *
     * @var string $name
     */
    public $name;

    /**
     * Tells if the index is unique
     *
     * @var bool $unique
     */
    public $unique;

    /**
     * Holds all the column names which the index consists of
     *
     * @var array $columns
     */
    public $columns = [];

    /**
     * Index constructor.
     *
     * @param array $columns
     * @param bool $unique
     * @param string $name
     */
    public function __construct(array $columns, $unique = false, $name = null)
    {
        $this->columns = $columns;
        $this->unique = $unique;
        $this->name = $name;

        if (is_null($name)) {
            $this->name = sprintf('%s_idx', implode('_', $this->columns));
        }
    }
}
