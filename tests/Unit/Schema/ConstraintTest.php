<?php

namespace Tests\Unit\Schema;

use PHPUnit\Framework\TestCase;
use Nbj\Database\Schema\Component\Constraint;

class ConstraintTest extends TestCase
{
    /** @test */
    public function it_can_de_instantiated()
    {
        $constraint = new Constraint('foreign', 'some-name');

        $this->assertInstanceOf(Constraint::class, $constraint);
    }

    /** @test */
    public function it_has_a_name()
    {
        $constraint = new Constraint('foreign', 'some-name');

        $this->assertEquals('some-name', $constraint->name);
    }

    /** @test */
    public function it_has_a_type()
    {
        $constraint = new Constraint('foreign', 'some-name');

        $this->assertEquals('foreign', $constraint->type);
    }

    /** @test */
    public function it_can_set_which_column_it_references()
    {
        $constraint = new Constraint('foreign', 'some-name');
        $constraint->references('some-column');

        $this->assertEquals('some-column', $constraint->references);
    }

    /** @test */
    public function it_can_set_which_table_it_references()
    {
        $constraint = new Constraint('foreign', 'some-name');
        $constraint->references('some-column')->on('some-table');

        $this->assertEquals('some-table', $constraint->on);
    }
}
