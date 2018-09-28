<?php

namespace Tests\Feature;

use PDO;
use Exception;
use PHPUnit\Framework\TestCase;
use Nbj\Database\DatabaseManager;
use Nbj\Database\Connection\Sqlite;
use Nbj\Database\QueryBuilder\Builder;
use Nbj\Database\Connection\Connection;
use Nbj\Database\Exception\NoTableWasSet;
use Nbj\Database\Exception\OperatorNotAllowed;
use Nbj\Database\Exception\FailedToPrepareQuery;
use Nbj\Database\Exception\InvalidConfiguration;
use Nbj\Database\Exception\DatabaseDriverNotFound;
use Nbj\Database\Exception\NoGlobalDatabaseManager;
use Nbj\Database\Exception\DatabaseConnectionWasNotFound;

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
        $this->expectException(InvalidConfiguration::class);

        $manager = new DatabaseManager;

        $manager->addConnection([
            'database' => ':memory:'
        ]);
    }

    /** @test */
    public function the_database_manger_throws_an_exception_if_a_driver_does_not_exist_for_the_driver_key()
    {
        $this->expectExceptionMessage('Database driver: this-is-not-a-database-driver was not found.');
        $this->expectException(DatabaseDriverNotFound::class);

        $manager = new DatabaseManager;

        $manager->addConnection([
            'driver' => 'this-is-not-a-database-driver'
        ]);
    }

    /** @test */
    public function the_database_manager_throws_an_exception_when_trying_to_get_a_non_existing_connection()
    {
        $this->expectExceptionMessage('DatabaseConnection: some-non-existing-connection was not found.');
        $this->expectException(DatabaseConnectionWasNotFound::class);

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
            $this->assertInstanceOf(NoGlobalDatabaseManager::class, $exception);
        }

        $this->assertNull($manager);
    }

    /** @test */
    public function the_manager_can_get_connections_statically_once_a_global_manager_has_been_set()
    {
        $this->registerGlobalDatabaseManager();

        $connection = DatabaseManager::connection('default');

        $this->assertInstanceOf(Connection::class, $connection);
        $this->assertInstanceOf(Sqlite::class, $connection);
    }

    /** @test */
    public function the_static_connection_method_defaults_to_the_default_connection_if_no_name_is_passed_to_it()
    {
        $this->registerGlobalDatabaseManager();

        $connection = DatabaseManager::connection();

        $this->assertEquals('default', $connection->getName());
        $this->assertInstanceOf(Connection::class, $connection);
        $this->assertInstanceOf(Sqlite::class, $connection);
    }

    /** @test */
    public function a_database_connection_can_initiate_a_new_query()
    {
        $this->registerGlobalDatabaseManager();

        $query = DatabaseManager::connection()->newQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertInstanceOf(Sqlite::class, $query->getConnection());
    }

    /** @test */
    public function it_can_get_all_rows_from_a_table()
    {
        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $result = $query
            ->table('people')
            ->all();

        $this->assertCount(2, $result);
        $first = array_shift($result);

        $this->assertObjectHasAttribute('first_name', $first);
        $this->assertObjectHasAttribute('last_name', $first);

        $this->assertEquals('john', $first->first_name);
        $this->assertEquals('doe', $first->last_name);
    }

    /** @test */
    public function it_can_get_all_rows_with_select_columns_from_a_table()
    {
        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $result = $query
            ->table('people')
            ->select(['first_name'])
            ->get();

        $this->assertCount(2, $result);
        $first = array_shift($result);

        $this->assertObjectHasAttribute('first_name', $first);
        $this->assertObjectNotHasAttribute('last_name', $first);

        $this->assertEquals('john', $first->first_name);
    }

    /** @test */
    public function it_takes_exception_to_query_failing()
    {
        $this->expectException(FailedToPrepareQuery::class);
        $this->expectExceptionMessage('Failed to execute query: SELECT not_a_column FROM people');

        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $query
            ->table('people')
            ->select(['not_a_column'])
            ->get();
    }

    /** @test */
    public function it_takes_exception_to_calling_all_if_no_table_is_set()
    {
        $this->expectException(NoTableWasSet::class);
        $this->expectExceptionMessage('No table was set for QueryBuilder');

        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $query->all();
    }

    /** @test */
    public function it_can_execute_a_select_statement_with_a_where_clause()
    {
        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $result = $query
            ->table('people')
            ->where('first_name', 'john')
            ->all();

        $this->assertCount(1, $result);
        $first = array_shift($result);

        $this->assertEquals('john', $first->first_name);
    }

    /** @test */
    public function it_can_execute_a_select_statement_with_two_where_clauses()
    {
        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $result = $query
            ->table('people')
            ->where('first_name', 'john')
            ->where('id', 1)
            ->all();

        $this->assertCount(1, $result);
        $first = array_shift($result);

        $this->assertEquals('john', $first->first_name);
        $this->assertEquals('doe', $first->last_name);
    }

    /** @test */
    public function it_can_execute_a_select_statement_with_or_where_clauses()
    {
        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $result = $query
            ->table('people')
            ->where('first_name', 'john')
            ->orWhere('id', 1)
            ->all();

        $this->assertCount(1, $result);
        $first = array_shift($result);

        $this->assertEquals('john', $first->first_name);
        $this->assertEquals('doe', $first->last_name);
    }

    /** @test */
    public function it_takes_exception_to_using_an_invalid_operator_in_a_where_clause()
    {
        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $this->expectExceptionMessage('Operator: invalid-operator is not a valid operator');
        $this->expectException(OperatorNotAllowed::class);

        $query
            ->table('people')
            ->where('first_name', 'invalid-operator', 'john')
            ->all();
    }

    /**
     * Prepares a table of people for tests
     *
     * @throws DatabaseConnectionWasNotFound
     * @throws NoGlobalDatabaseManager
     */
    protected function prepareTestTableWithData()
    {
        $createTableSql = "create table people (id INTEGER PRIMARY KEY AUTOINCREMENT, first_name TEXT NOT NULL, last_name TEXT NOT NULL)";
        $statement = DatabaseManager::connection()->getPdo()->prepare($createTableSql);
        $statement->execute();

        $insertSql = "insert into people (first_name,last_name) values ('john','doe'),('jane','doe')";
        $statement = DatabaseManager::connection()->getPdo()->prepare($insertSql);
        $statement->execute();
    }

    /**
     * Registers a global DatabaseManger with an sqlite connection
     *
     * @throws DatabaseDriverNotFound
     * @throws InvalidConfiguration
     */
    protected function registerGlobalDatabaseManager()
    {
        (new DatabaseManager)
            ->addConnection(['driver' => 'sqlite', 'database' => ':memory:'])
            ->setAsGlobal();
    }
}
