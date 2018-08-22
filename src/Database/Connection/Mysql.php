<?php

namespace Nbj\Database\Connection;

use PDO;
use Nbj\Database\Exception\InvalidConfiguration;

class Mysql extends Connection
{
    /**
     * Creates the PDO connection
     *
     * @param array $options
     *
     * @return void
     *
     * @throws InvalidConfiguration
     */
    public function connect(array $options = [])
    {
        if (!isset($this->config['database'])) {
            throw new InvalidConfiguration('No "database" key not found in config');
        }

        if (!isset($this->config['host'])) {
            throw new InvalidConfiguration('No "host" key not found in config');
        }

        if (!isset($this->config['port'])) {
            throw new InvalidConfiguration('No "port" key not found in config');
        }

        if (!isset($this->config['username'])) {
            throw new InvalidConfiguration('No "username" key not found in config');
        }

        if (!isset($this->config['password'])) {
            throw new InvalidConfiguration('No "password" key not found in config');
        }

        $host = $this->config['host'];
        $port = $this->config['port'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $database = $this->config['database'];

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $database);

        $this->pdo = new PDO($dsn, $username, $password, $options);
    }
}
