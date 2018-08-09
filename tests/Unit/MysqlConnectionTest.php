<?php

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Nbj\Database\Connection\Connection;
use Nbj\Database\Connection\Mysql as MysqlConnection;
use Nbj\Database\Exception\InvalidConfigurationException;

class MysqlConnectionTest extends TestCase
{
    /** @test */
    public function it_can_be_newed_up()
    {
        // This test requires a real mysql service to be running
        // If you have docker and docker-compose installed
        // a docker-compose.yml file is provided
        // simply run 'docker-compose up -d'
        $connection = new MysqlConnection([
            'driver'   => 'mysql',
            'host'     => '127.0.0.1',
            'port'     => '3306',
            'username' => 'www',
            'password' => 'secret',
            'database' => 'test',
        ]);

        $this->assertInstanceOf(MysqlConnection::class, $connection);
        $this->assertInstanceOf(Connection::class, $connection);
    }

    /** @test */
    public function it_takes_exception_to_missing_host_key_in_configuration()
    {
        $connection = null;

        try {
            $connection = new MysqlConnection([
                'port'     => '3306',
                'username' => 'www',
                'password' => 'secret',
                'database' => 'test',
            ]);
        } catch (Exception $exception) {
            $this->assertEquals('No "host" key not found in config', $exception->getMessage());
            $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        }

        $this->assertNull($connection);
    }

    /** @test */
    public function it_takes_exception_to_missing_port_key_in_configuration()
    {
        $connection = null;

        try {
            $connection = new MysqlConnection([
                'host'     => '127.0.0.1',
                'username' => 'www',
                'password' => 'secret',
                'database' => 'test',
            ]);
        } catch (Exception $exception) {
            $this->assertEquals('No "port" key not found in config', $exception->getMessage());
            $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        }

        $this->assertNull($connection);
    }

    /** @test */
    public function it_takes_exception_to_missing_username_key_in_configuration()
    {
        $connection = null;

        try {
            $connection = new MysqlConnection([
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'password' => 'secret',
                'database' => 'test',
            ]);
        } catch (Exception $exception) {
            $this->assertEquals('No "username" key not found in config', $exception->getMessage());
            $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        }

        $this->assertNull($connection);
    }

    /** @test */
    public function it_takes_exception_to_missing_password_key_in_configuration()
    {
        $connection = null;

        try {
            $connection = new MysqlConnection([
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'username' => 'www',
                'database' => 'test',
            ]);
        } catch (Exception $exception) {
            $this->assertEquals('No "password" key not found in config', $exception->getMessage());
            $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        }

        $this->assertNull($connection);
    }

    /** @test */
    public function it_takes_exception_to_missing_database_key_in_configuration()
    {
        $connection = null;

        try {
            $connection = new MysqlConnection([
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'username' => 'www',
                'password' => 'secret',
            ]);
        } catch (Exception $exception) {
            $this->assertEquals('No "database" key not found in config', $exception->getMessage());
            $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        }

        $this->assertNull($connection);
    }
}
