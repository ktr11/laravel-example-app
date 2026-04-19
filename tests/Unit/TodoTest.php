<?php

namespace Tests\Unit;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_is_cast_to_boolean(): void
    {
        $todo = Todo::factory()->create(['completed' => 0]);

        $this->assertIsBool($todo->completed);
        $this->assertFalse($todo->completed);
    }

    public function test_default_completed_is_false(): void
    {
        $todo = Todo::factory()->create();

        $this->assertFalse($todo->completed);
    }

    public function test_completed_state_can_be_toggled(): void
    {
        $todo = Todo::factory()->create(['completed' => false]);

        $todo->update(['completed' => !$todo->completed]);

        $this->assertTrue($todo->fresh()->completed);
    }

    public function test_fillable_fields(): void
    {
        $todo = new Todo();

        $this->assertEquals(['title', 'completed'], $todo->getFillable());
    }
}
