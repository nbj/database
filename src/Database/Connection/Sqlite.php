<?php

namespace Nbj\Database\Connection;

use PDO;
use Nbj\Database\Exception\InvalidConfigurationException;

class Sqlite extends Connection
{
    /**
     * Creates the PDO connection
     *
     * @param array $options
     *
     * @return void
     *
     * @throws InvalidConfigurationException
     */
    public function connect(array $options = [])
    {
        if (!isset($this->config['database'])) {
            throw new InvalidConfigurationException('No "database" key not found in config');
        }

        $dsn = sprintf('sqlite:%s', $this->config['database']);

        $this->pdo = new PDO($dsn, null, null, $options);
    }
}
