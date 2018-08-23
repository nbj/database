<?php

namespace Tests\Unit\Schema;

use PHPUnit\Framework\TestCase;
use Nbj\Database\Schema\Component\Index;

class IndexTest extends TestCase
{
    /** @test */
    public function it_can_de_instantiated()
    {
        $index = new Index(['created_at', 'updated_at']);

        $this->assertInstanceOf(Index::class, $index);
    }

    /** @test */
    public function it_has_a_name_which_defaults_to_a_concatenation_of_column_names()
    {
        $index = new Index(['created_at', 'updated_at']);

        $this->assertEquals('created_at_updated_at_idx', $index->name);
    }

    /** @test */
    public function it_can_be_unique()
    {
        $indexA = new Index(['created_at', 'updated_at']);
        $indexB = new Index(['created_at', 'updated_at'], true);

        $this->assertFalse($indexA->unique);
        $this->assertTrue($indexB->unique);
    }

    /** @test */
    public function its_name_can_be_set()
    {
        $index = new Index(['created_at', 'updated_at'], false, 'index-name');

        $this->assertEquals('index-name', $index->name);
    }
}
