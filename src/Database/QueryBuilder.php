<?php

namespace Nbj\Database;

use PDO;
use Nbj\Database\Exception\NoTableWasSet;
use Nbj\Database\Exception\GrammarDoesNotExist;
use Nbj\Database\Exception\FailedToExecuteQuery;

class QueryBuilder
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
     * @return QueryBuilder
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
     * Performs a select * query on a specified table
     *
     * @return array
     *
     * @throws FailedToExecuteQuery
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
     * @throws FailedToExecuteQuery
     * @throws NoTableWasSet
     */
    public function get()
    {
        if (!$this->table) {
            throw new NoTableWasSet;
        }

        $grammar = $this->getGrammar();
        $sql = $grammar->compileSelect($this->table, $this->columns);
        $pdo = $this->getConnection()->getPdo();
        $statement = $pdo->prepare($sql);

        if (!$statement->execute()) {
            throw new FailedToExecuteQuery($statement);
        }

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}
