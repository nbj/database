<?php

namespace Tests\Unit\Schema;

use PHPUnit\Framework\TestCase;
use Nbj\Database\Schema\Blueprint;
use Nbj\Database\Schema\Component\Index;
use Nbj\Database\Schema\Component\Column;

class BlueprintTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $blueprint = new Blueprint;

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }

    /** @test */
    public function columns_added_are_instances_of_column()
    {
        $table = new Blueprint;
        $table->integer('id');
        $columns = $table->getColumns();

        $this->assertInstanceOf(Column::class, $columns['id']);
    }

    /** @test */
    public function it_can_add_an_integer_column_to_it()
    {
        $table = new Blueprint;

        $table->integer('id');

        $columns = $table->getColumns();
        $this->assertCount(1, $columns);
        $this->assertTrue(array_key_exists('id', $columns));
        $this->assertEquals('id', $columns['id']->name);
        $this->assertEquals('integer', $columns['id']->type);
    }

    /** @test */
    public function an_integer_column_has_a_size()
    {
        $table = new Blueprint;

        $table->integer('id', 10);

        $columns = $table->getColumns();
        $this->assertEquals(10, $columns['id']->size);
    }

    /** @test */
    public function an_integer_column_size_defaults_to_11()
    {
        $table = new Blueprint;

        $table->integer('id');

        $columns = $table->getColumns();
        $this->assertEquals(11, $columns['id']->size);
    }

    /** @test */
    public function it_can_add_a_string_column_to_it()
    {
        $table = new Blueprint;

        $table->string('name');

        $columns = $table->getColumns();
        $this->assertCount(1, $columns);
        $this->assertTrue(array_key_exists('name', $columns));
        $this->assertEquals('name', $columns['name']->name);
        $this->assertEquals('string', $columns['name']->type);
    }

    /** @test */
    public function a_string_column_has_a_size()
    {
        $table = new Blueprint;

        $table->string('name', 64);

        $columns = $table->getColumns();
        $this->assertEquals(64, $columns['name']->size);
    }

    /** @test */
    public function a_string_column_size_defaults_to_255()
    {
        $table = new Blueprint;

        $table->string('name');

        $columns = $table->getColumns();
        $this->assertEquals(255, $columns['name']->size);
    }

    /** @test */
    public function it_can_add_a_boolean_column_to_it()
    {
        $table = new Blueprint;

        $table->boolean('active');

        $columns = $table->getColumns();
        $this->assertCount(1, $columns);
        $this->assertTrue(array_key_exists('active', $columns));
        $this->assertEquals('active', $columns['active']->name);
        $this->assertEquals('boolean', $columns['active']->type);
    }

    /** @test */
    public function a_boolean_column_size_defaults_to_1()
    {
        $table = new Blueprint;

        $table->boolean('active');

        $columns = $table->getColumns();
        $this->assertEquals(1, $columns['active']->size);
    }

    /** @test */
    public function it_can_add_a_datetime_column_to_it()
    {
        $table = new Blueprint;

        $table->datetime('created_at');

        $columns = $table->getColumns();
        $this->assertCount(1, $columns);
        $this->assertTrue(array_key_exists('created_at', $columns));
        $this->assertEquals('created_at', $columns['created_at']->name);
        $this->assertEquals('datetime', $columns['created_at']->type);
    }

    /** @test */
    public function a_datetime_column_size_defaults_to_null_as_it_is_irrelevant()
    {
        $table = new Blueprint;

        $table->datetime('created_at');

        $columns = $table->getColumns();
        $this->assertNull($columns['created_at']->size);
    }

    /** @test */
    public function it_can_add_indices()
    {
        $table = new Blueprint;
        $table->integer('id');
        $table->string('name')->index();
        $table->datetime('created_at');
        $table->datetime('updated_at');
        $this->assertCount(1, $table->getIndices());

        $table->index(['created_at', 'updated_at']);

        $this->assertCount(2, $table->getIndices());
        $indices = $table->getIndices();
        $this->assertInstanceOf(Index::class, $indices['name_idx']);
        $this->assertInstanceOf(Index::class, $indices['created_at_updated_at_idx']);
        $this->assertFalse($indices['name_idx']->unique);
        $this->assertFalse($indices['created_at_updated_at_idx']->unique);
    }

    /** @test */
    public function it_can_add_unique_indices()
    {
        $table = new Blueprint;
        $table->integer('id');
        $table->string('email')->unique();
        $table->datetime('created_at');
        $table->datetime('updated_at');
        $this->assertCount(1, $table->getIndices());

        $table->unique(['created_at', 'updated_at']);

        $this->assertCount(2, $table->getIndices());
        $indices = $table->getIndices();
        $this->assertInstanceOf(Index::class, $indices['email_idx']);
        $this->assertInstanceOf(Index::class, $indices['created_at_updated_at_idx']);
        $this->assertTrue($indices['email_idx']->unique);
        $this->assertTrue($indices['created_at_updated_at_idx']->unique);
    }

    /** @test */
    public function it_can_add_foreign_constraints()
    {
        $table = new Blueprint;
        $table->integer('id');

        $table->foreign('foreign_id')
            ->references('foreign_id')
            ->on('foreign_table');

        $this->assertCount(1, $table->getConstraints());
    }
}
