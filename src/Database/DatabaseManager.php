<?php

namespace Nbj\Database;

class DatabaseManager
{
    /**
     * Holds the Manager instance once set as global
     *
     * @var DatabaseManager $instance
     */
    protected static $instance;

    /**
     * Holds a map over registered database drivers
     *
     * @var array $drivers
     */
    protected $drivers = [
        'mysql'  => Connection\Mysql::class,
        'sqlite' => Connection\Sqlite::class,
    ];

    /**
     * Holds all registered database connections
     *
     * @var array $connections
     */
    protected $connections = [];

    /**
     * Gets the global DatabaseManager
     *
     * @return DatabaseManager
     *
     * @throws Exception\NoGlobalDatabaseManagerException
     */
    public static function getGlobal()
    {
        if (self::$instance) {
            return self::$instance;
        }

        throw new Exception\NoGlobalDatabaseManagerException;
    }

    /**
     * Adds a connection to the manager
     *
     * @param array $config
     * @param string $name
     *
     * @return $this
     *
     * @throws Exception\DatabaseDriverNotFoundException
     * @throws Exception\InvalidConfigurationException
     */
    public function addConnection(array $config, $name = 'default')
    {
        // Make sure configuration contains which driver to use
        if (!isset($config['driver'])) {
            throw new Exception\InvalidConfigurationException('No "driver" key not found in config');
        }

        // Make sure that driver actually exists
        if (!array_key_exists($config['driver'], $this->drivers)) {
            throw new Exception\DatabaseDriverNotFoundException($config['driver']);
        }

        // Create a new connection instance of the driver
        $this->connections[$name] = new $this->drivers[$config['driver']]($config);

        return $this;
    }

    /**
     * Gets a specific database connection
     *
     * @param string $connectionName
     *
     * @return Connection\Connection
     *
     * @throws Exception\DatabaseConnectionWasNotFoundException
     */
    public function getConnection($connectionName)
    {
        if (!array_key_exists($connectionName, $this->connections)) {
            throw new Exception\DatabaseConnectionWasNotFoundException($connectionName);
        }

        return $this->connections[$connectionName];
    }

    /**
     * Gets the default connection
     *
     * @return Connection\Connection
     *
     * @throws Exception\DatabaseConnectionWasNotFoundException
     */
    public function getDefaultConnection()
    {
        return $this->getConnection('default');
    }

    /**
     * Sets this instance as the global database manager
     *
     * @return $this
     */
    public function setAsGlobal()
    {
        self::$instance = $this;

        return $this;
    }
}
