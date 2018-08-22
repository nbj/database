<?php

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Nbj\Database\Connection\Connection;
use Nbj\Database\Exception\InvalidConfiguration;
use Nbj\Database\Connection\Sqlite as SqliteConnection;

class SqliteConnectionTest extends TestCase
{
    /** @test */
    public function it_can_be_newed_up()
    {
        $connection = new SqliteConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);

        $this->assertInstanceOf(SqliteConnection::class, $connection);
        $this->assertInstanceOf(Connection::class, $connection);
    }

    /** @test */
    public function it_takes_exception_to_missing_database_key_in_configuration()
    {
        $connection = null;

        try {
            $connection = new SqliteConnection([]);
        } catch (Exception $exception) {
            $this->assertEquals('No "database" key not found in config', $exception->getMessage());
            $this->assertInstanceOf(InvalidConfiguration::class, $exception);
        }

        $this->assertNull($connection);
    }
}
