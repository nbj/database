<?php

namespace Tests\Feature;

use Nbj\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Nbj\Database\Connection\Sqlite;
use Nbj\Database\Connection\Connection;

class QueryBuilderTest extends TestCase
{
    /** @test */
    public function it_can_have_a_connection_set()
    {
        $connection = $this->createConnection();

        $queryA = new QueryBuilder($connection);
        $this->assertInstanceOf(Connection::class, $queryA->getConnection());
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
