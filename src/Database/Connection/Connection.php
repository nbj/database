<?php

namespace Nbj\Database\Connection;

abstract class Connection
{
    /**
     * Holds the underlying PDO object
     *
     * @var PDO $pdo
     */
    protected $pdo;

    /**
     * Holds the configuration passed in on instantiation
     *
     * @var array $config
     */
    protected $config;

    /**
     * Connection constructor.
     *
     * @param array $config
     * @param array $options
     */
    public function __construct(array $config, array $options = [])
    {
        $this->config = $config;

        $this->connect($options);
    }

    /**
     * Gets the underlying PDO connection object
     *
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Creates the PDO connection
     *
     * @param array $options
     *
     * @return void
     *
     * @throws InvalidConfigurationException
     */
    abstract public function connect(array $options = []);
}
