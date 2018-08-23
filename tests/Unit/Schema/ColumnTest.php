<?php

namespace Tests\Unit\Schema;

use PHPUnit\Framework\TestCase;
use Nbj\Database\Schema\Component\Column;

class ColumnTest extends TestCase
{
    /** @test */
    public function it_has_a_name()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertEquals('id', $column->name);
    }

    /** @test */
    public function it_has_a_type()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertEquals('integer', $column->type);
    }

    /** @test */
    public function it_has_a_size()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertEquals(10, $column->size);
    }

    /** @test */
    public function its_default_value_is_by_default_set_to_null()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertNull($column->defaultValue);
    }

    /** @test */
    public function it_is_by_default_not_primary()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertFalse($column->isPrimary);
    }

    /** @test */
    public function it_is_by_default_not_unique()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertFalse($column->isUnique);
    }

    /** @test */
    public function it_is_by_default_not_nullable()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertFalse($column->isNullable);
    }

    /** @test */
    public function it_is_by_default_not_unsigned()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertFalse($column->isUnsigned);
    }

    /** @test */
    public function it_is_by_default_not_indexed()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertFalse($column->hasIndex);
    }

    /** @test */
    public function it_is_by_default_not_auto_incrementing()
    {
        $column = new Column('integer', 'id', 10);

        $this->assertFalse($column->autoIncrements);
    }

    /** @test */
    public function it_can_be_set_as_primary()
    {
        $column = new Column('integer', 'id', 10);
        $this->assertFalse($column->isPrimary);

        $column->primary();

        $this->assertTrue($column->isPrimary);
    }

    /** @test */
    public function it_can_be_set_as_indexed()
    {
        $column = new Column('integer', 'id', 10);
        $this->assertFalse($column->hasIndex);

        $column->index();

        $this->assertTrue($column->hasIndex);
    }

    /** @test */
    public function it_can_be_set_as_unique()
    {
        $column = new Column('integer', 'id', 10);
        $this->assertFalse($column->isUnique);

        $column->unique();

        $this->assertTrue($column->isUnique);
    }

    /** @test */
    public function it_can_be_set_as_auto_incrementing()
    {
        $column = new Column('integer', 'id', 10);
        $this->assertFalse($column->autoIncrements);

        $column->autoIncrement();

        $this->assertTrue($column->autoIncrements);
    }

    /** @test */
    public function it_can_be_set_as_unsigned()
    {
        $column = new Column('integer', 'id', 10);
        $this->assertFalse($column->isUnsigned);

        $column->unsigned();

        $this->assertTrue($column->isUnsigned);
    }

    /** @test */
    public function it_can_be_set_as_nullable()
    {
        $column = new Column('integer', 'id', 10);
        $this->assertFalse($column->isNullable);

        $column->nullable();

        $this->assertTrue($column->isNullable);
    }

    /** @test */
    public function it_can_have_its_default_value_set()
    {
        $column = new Column('integer', 'id', 10);
        $this->assertNull($column->defaultValue);

        $column->default('new-default-value');

        $this->assertEquals('new-default-value', $column->defaultValue);
    }
}
