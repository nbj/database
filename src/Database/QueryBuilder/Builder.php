<?php

namespace Nbj\Database\QueryBuilder;

use PDO;
use Nbj\Database\Grammar;
use Nbj\Database\Connection;
use Nbj\Database\Exception\NoTableWasSet;
use Nbj\Database\Exception\OperatorNotAllowed;
use Nbj\Database\Exception\GrammarDoesNotExist;
use Nbj\Database\Exception\FailedToPrepareQuery;

class Builder
{
    /**
     * Holds all the registered grammars
     *
     * @var array $grammars
     */
    protected static $grammars = [
        Connection\Sqlite::class => Grammar\Sqlite::class,
        Connection\Mysql::class  => Grammar\Mysql::class,
    ];

    /**
     * List of valid operators
     *
     * @var array $operators
     */
    protected $operators = [
        '=', '<>', '>=', '<=', '>', '<', '!='
    ];

    /**
     * Holds the connection to send the query to
     *
     * @var Connection\Connection $connection
     */
    protected $connection;

    /**
     * Holds the grammar once resolved
     *
     * @var Grammar\Grammar $grammar
     */
    protected $grammar;

    /**
     * Holds the table for the query
     *
     * @var string $table
     */
    protected $table;

    /**
     * Holds all columns for the query
     *
     * @var array $columns
     */
    protected $columns = [
        '*'
    ];

    /**
     * Holds all where clauses
     *
     * @var array $whereClauses
     */
    protected $whereClauses = [];

    /**
     * QueryBuilder constructor.
     *
     * @param Connection\Connection $connection
     *
     * @throws GrammarDoesNotExist
     */
    public function __construct(Connection\Connection $connection)
    {
        if (!array_key_exists($connection->getDriver(), self::$grammars)) {
            throw new GrammarDoesNotExist($connection->getDriver());
        }

        $grammar = new self::$grammars[$connection->getDriver()]($this);

        $this->setConnection($connection);
        $this->setGrammar($grammar);
    }

    /**
     * Gets the connection
     *
     * @return Connection\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Sets the connection to use for the query
     *
     * @param Connection\Connection $connection
     *
     * @return $this
     */
    public function setConnection(Connection\Connection $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Gets the grammar use for the query builder instance
     *
     * @return Grammar\Grammar
     */
    public function getGrammar()
    {
        return $this->grammar;
    }

    /**
     * Sets the grammar to use for this query builder
     *
     * @param Grammar\Grammar $grammar
     *
     * @return $this
     */
    public function setGrammar(Grammar\Grammar $grammar)
    {
        $this->grammar = $grammar;

        return $this;
    }

    /**
     * Sets the table for the query
     *
     * @param string $table
     *
     * @return Builder
     */
    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Sets which columns to select
     *
     * @param array $columns
     *
     * @return $this
     */
    public function select(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Adds a Where clause to the query
     *
     * @param string $column
     * @param string $operator
     * @param string|null $value
     *
     * @return $this
     *
     * @throws OperatorNotAllowed
     */
    public function where($column, $operator, $value = null)
    {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->guardAgainstInvalidOperator($operator);

        $this->whereClauses[] = new WhereClause($column, $operator, $value);

        return $this;
    }

    /**
     * Adds a orWhere clause to the query
     *
     * @param string $column
     * @param string $operator
     * @param string|null $value
     *
     * @return $this
     *
     * @throws OperatorNotAllowed
     */
    public function orWhere($column, $operator, $value = null)
    {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->guardAgainstInvalidOperator($operator);

        $this->whereClauses[] = new WhereClause($column, $operator, $value, true);

        return $this;
    }

    /**
     * Performs a select * query on a specified table
     *
     * @return array
     *
     * @throws FailedToPrepareQuery
     * @throws NoTableWasSet
     */
    public function all()
    {
        $this->columns = ['*'];

        return $this->get();
    }

    /**
     * Performs a select query on a specified table with the specified columns
     *
     * @return array
     *
     * @throws FailedToPrepareQuery
     * @throws NoTableWasSet
     */
    public function get()
    {
        $this->guardAgainstNoTableBeingSet();

        $sql = $this
            ->getGrammar()
            ->compileSelect($this->table, $this->columns, $this->whereClauses);

        $statement = $this
            ->getConnection()
            ->getPdo()
            ->prepare($sql);

        if ($statement === false) {
            throw new FailedToPrepareQuery($sql);
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Returns the SQL for the query
     *
     * @return string
     *
     * @throws NoTableWasSet
     */
    public function toSql()
    {
        $this->guardAgainstNoTableBeingSet();

        return $this
            ->getGrammar()
            ->compileSelect($this->table, $this->columns, $this->whereClauses);
    }

    /**
     * Throws an exception if an invalid operator is being used
     *
     * @param string $operator
     *
     * @throws OperatorNotAllowed
     */
    protected function guardAgainstInvalidOperator($operator)
    {
        if (!in_array($operator, $this->operators)) {
            throw new OperatorNotAllowed($operator);
        }
    }

    /**
     * Throws an exception if no table has been set
     *
     * @throws NoTableWasSet
     */
    protected function guardAgainstNoTableBeingSet()
    {
        if (!$this->table) {
            throw new NoTableWasSet;
        }
    }
}
