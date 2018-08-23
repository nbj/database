<?php

namespace Nbj\Database;

use Nbj\Database\Exception\GrammarDoesNotExist;

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
     * @var Connection $connection
     */
    protected $connection;

    /**
     * Holds the grammar once resolved
     *
     * @var Grammar $grammar
     */
    protected $grammar;

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
     * @return Connection
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
     * @return Grammar
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
}
