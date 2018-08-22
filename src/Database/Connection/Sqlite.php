<?php

namespace Nbj\Database\Connection;

use PDO;
use Nbj\Database\Exception\InvalidConfiguration;

class Sqlite extends Connection
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

        $dsn = sprintf('sqlite:%s', $this->config['database']);

        $this->pdo = new PDO($dsn, null, null, $options);
    }
}
