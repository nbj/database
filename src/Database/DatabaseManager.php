<?php

namespace Nbj\Database;

use Nbj\Database\Exception\InvalidConfigurationException;
use Nbj\Database\Exception\DatabaseDriverNotFoundException;
use Nbj\Database\Exception\NoGlobalDatabaseManagerException;

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
     * Holds the default database connection
     *
     * @var Connection\Connection $defaultConnection
     */
    protected $defaultConnection;

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
     * @throws NoGlobalDatabaseManagerException
     */
    public static function getGlobal()
    {
        if (self::$instance) {
            return self::$instance;
        }

        throw new NoGlobalDatabaseManagerException;
    }

    /**
     * Adds a connection to the manager
     *
     * @param array $config
     * @param bool $defaultConnection
     *
     * @return $this
     *
     * @throws DatabaseDriverNotFoundException
     * @throws InvalidConfigurationException
     */
    public function addConnection(array $config, $defaultConnection = false)
    {
        // Make sure configuration contains which driver to use
        if (!isset($config['driver'])) {
            throw new InvalidConfigurationException('No "driver" key not found in config');
        }

        // Make sure that driver actually exists
        if (!array_key_exists($config['driver'], $this->drivers)) {
            throw new DatabaseDriverNotFoundException($config['driver']);
        }

        // Create a new connection instance of the driver
        $connection = new $this->drivers[$config['driver']]($config);

        if ($defaultConnection) {
            $this->defaultConnection = $connection;
        }

        $this->connections[] = $connection;

        return $this;
    }

    /**
     * Gets the default connection
     *
     * @return Connection\Connection
     */
    public function getDefaultConnection()
    {
        return $this->defaultConnection;
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
