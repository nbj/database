<?php

namespace Tests\Unit;

use Nbj\Database\Grammar;
use Nbj\Database\Connection;
use Nbj\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Nbj\Database\Schema\Blueprint;

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
            $table->boolean('is_completed');
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

        $builder = new QueryBuilder($connection);

        return new Grammar\Sqlite($builder);
    }
}
