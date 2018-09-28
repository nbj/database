<?php

namespace Tests\Unit;

use Nbj\Database\Grammar;
use Nbj\Database\Connection;
use PHPUnit\Framework\TestCase;
use Nbj\Database\DatabaseManager;
use Nbj\Database\Schema\Blueprint;
use Nbj\Database\QueryBuilder\Builder;
use Nbj\Database\Exception\InvalidConfiguration;
use Nbj\Database\Exception\DatabaseDriverNotFound;
use Nbj\Database\Exception\NoGlobalDatabaseManager;
use Nbj\Database\Exception\DatabaseConnectionWasNotFound;

class SqliteGrammarTest extends TestCase
{
    /** @test */
    public function it_compiles_a_create_table_query()
    {
        /** @var Grammar\Sqlite $grammar */
        $grammar = $this->createSqliteGrammar();

        $blueprint = Blueprint::create('task', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->string('task');
            $table->boolean('is_completed')->default(false);
            $table->datetime('created_at');
            $table->datetime('updated_at');
        });

        $sql = $grammar->compileCreateTable($blueprint);

        $this->assertTrue(true);
    }
    
    public function createSqliteGrammar()
    {
        $connection = $this->createMock(Connection\Connection::class);
        $connection->method('getDriver')->willReturn(Connection\Sqlite::class);

        $builder = new Builder($connection);

        return new Grammar\Sqlite($builder);
    }

    /** @test */
    public function it_compiles_a_select_query_with_where_clauses()
    {
        $this->registerGlobalDatabaseManager();
        $this->prepareTestTableWithData();

        $query = DatabaseManager::connection()->newQuery();

        $sql = $query
            ->table('people')
            ->where('first_name', 'john')
            ->where('id', 1)
            ->toSql();

        $this->assertEquals("SELECT * FROM people WHERE first_name = 'john' AND id = 1", $sql);
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
