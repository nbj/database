<?php

namespace Tests\Feature;

use Nbj\Database\Grammar;
use Nbj\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Nbj\Database\Connection\Sqlite;
use Nbj\Database\Connection\Connection;
use Nbj\Database\Exception\GrammarDoesNotExist;

class QueryBuilderTest extends TestCase
{
    /** @test */
    public function it_can_have_a_connection_set()
    {
        $connection = $this->createConnection();

        $query = new QueryBuilder($connection);
        $this->assertInstanceOf(Connection::class, $query->getConnection());
    }

    /** @test */
    public function it_takes_exception_to_grammar_not_existing()
    {
        $connection = $this->createMock(Connection::class);
        $connection->method('getDriver')->willReturn('this-is-not-a-valid-driver');

        $this->expectException(GrammarDoesNotExist::class);
        $this->expectExceptionMessage('Grammar for connection type: this-is-not-a-valid-driver was not found');

        $query = new QueryBuilder($connection);

        $this->assertNull($query);
    }

    /** @test */
    public function it_has_a_grammar_based_on_the_connection_driver()
    {
        $connection = $this->createConnection();

        $query = new QueryBuilder($connection);

        $this->assertInstanceOf(Grammar\Sqlite::class, $query->getGrammar());
    }

    /**
     * Creates a fake connection instance
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createConnection()
    {
        $connectionMock = $this->createMock(Connection::class);
        $connectionMock->method('getDriver')->willReturn(Sqlite::class);

        return $connectionMock;
    }
}
