<?php

namespace Tests\Feature;

use PDO;
use PHPUnit\Framework\TestCase;
use Nbj\Database\Connection\Sqlite as SqliteConnection;

class DatabaseTest extends TestCase
{
    /** @test */
    public function a_connection_has_an_underlying_pdo_instance()
    {
        $connection = new SqliteConnection(['database' => ':memory:']);

        $this->assertInstanceOf(PDO::class, $connection->getPdo());
    }
}
