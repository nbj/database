<?php

namespace Tests\Feature;

use PDO;
use Exception;
use PHPUnit\Framework\TestCase;
use Nbj\Database\DatabaseManager;
use Nbj\Database\Connection\Sqlite;
use Nbj\Database\Connection\Connection;
use Nbj\Database\Exception\InvalidConfigurationException;
use Nbj\Database\Exception\DatabaseDriverNotFoundException;
use Nbj\Database\Exception\NoGlobalDatabaseManagerException;
use Nbj\Database\Exception\DatabaseConnectionWasNotFoundException;

class DatabaseTest extends TestCase
{
    /** @test */
    public function a_database_manager_exists_and_can_be_newed_up()
    {
        $manager = new DatabaseManager;

        $this->assertInstanceOf(DatabaseManager::class, $manager);
    }

    /** @test */
    public function a_database_can_have_connections_added_to_it()
    {
        $manager = new DatabaseManager;

        $manager->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:'
        ]);

        $this->assertInstanceOf(Connection::class, $manager->getDefaultConnection());
        $this->assertInstanceOf(Sqlite::class, $manager->getDefaultConnection());
    }

    /** @test */
    public function a_connection_has_an_underlying_pdo_instance()
    {
        $manager = new DatabaseManager;

        $manager->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:'
        ]);

        $connection = $manager->getDefaultConnection();

        $this->assertInstanceOf(PDO::class, $connection->getPdo());
    }

    /** @test */
    public function the_database_manager_throws_an_exception_if_the_provided_configuration_does_not_contain_a_driver_key()
    {
        $this->expectExceptionMessage('No "driver" key not found in config');
        $this->expectException(InvalidConfigurationException::class);

        $manager = new DatabaseManager;

        $manager->addConnection([
            'database' => ':memory:'
        ]);
    }

    /** @test */
    public function the_database_manger_throws_an_exception_if_a_driver_does_not_exist_for_the_driver_key()
    {
        $this->expectExceptionMessage('Database driver: this-is-not-a-database-driver was not found.');
        $this->expectException(DatabaseDriverNotFoundException::class);

        $manager = new DatabaseManager;

        $manager->addConnection([
            'driver' => 'this-is-not-a-database-driver'
        ]);
    }

    /** @test */
    public function the_database_manager_throws_an_exception_when_trying_to_get_a_non_existing_connection()
    {
        $this->expectExceptionMessage('DatabaseConnection: some-non-existing-connection was not found.');
        $this->expectException(DatabaseConnectionWasNotFoundException::class);

        $manager = new DatabaseManager;

        $manager->getConnection('some-non-existing-connection');
    }

    /** @test */
    public function a_database_manager_can_be_set_as_global()
    {
        $manager = new DatabaseManager;

        $manager->setAsGlobal();

        $this->assertInstanceOf(DatabaseManager::class, DatabaseManager::getGlobal());
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function an_exception_is_thrown_when_trying_to_get_a_non_existing_global_manager()
    {
        $manager = null;

        try {
            $manager = DatabaseManager::getGlobal();
        } catch (Exception $exception) {
            $this->assertEquals('No global DatabaseManager has been set', $exception->getMessage());
            $this->assertInstanceOf(NoGlobalDatabaseManagerException::class, $exception);
        }

        $this->assertNull($manager);
    }

    /** @test */
    public function the_manager_can_get_connections_statically_once_a_global_manager_has_been_set()
    {
        (new DatabaseManager)
            ->addConnection(['driver' => 'sqlite', 'database' => ':memory:'])
            ->setAsGlobal();

        $connection = DatabaseManager::connection('default');

        $this->assertInstanceOf(Connection::class, $connection);
        $this->assertInstanceOf(Sqlite::class, $connection);
    }

    /** @test */
    public function the_static_connection_method_defaults_to_the_default_connection_if_no_name_is_passed_to_it()
    {
        (new DatabaseManager)
            ->addConnection(['driver' => 'sqlite', 'database' => ':memory:'])
            ->setAsGlobal();

        $connection = DatabaseManager::connection();

        $this->assertEquals('default', $connection->getName());
        $this->assertInstanceOf(Connection::class, $connection);
        $this->assertInstanceOf(Sqlite::class, $connection);
    }
}
