<?php

namespace Tests\Feature;

use Nbj\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Nbj\Database\Connection\Sqlite;
use Nbj\Database\Connection\Connection;
use Nbj\Database\Exception\GrammarDoesNotExistException;

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

        $this->expectException(GrammarDoesNotExistException::class);
        $this->expectExceptionMessage('Grammar for connection type: this-is-not-a-valid-driver was not found');

        $query = new QueryBuilder($connection);

        $this->assertNull($query);
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