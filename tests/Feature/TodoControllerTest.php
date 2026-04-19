<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_todos(): void
    {
        Todo::factory()->create(['title' => 'Buy milk']);
        Todo::factory()->create(['title' => 'Write tests']);

        $response = $this->get(route('todos.index'));

        $response->assertStatus(200);
        $response->assertSee('Buy milk');
        $response->assertSee('Write tests');
    }

    public function test_root_redirects_to_todos_index(): void
    {
        $this->get('/')->assertRedirect(route('todos.index'));
    }

    public function test_store_creates_todo(): void
    {
        $response = $this->post(route('todos.store'), ['title' => 'New task']);

        $response->assertRedirect(route('todos.index'));
        $this->assertDatabaseHas('todos', ['title' => 'New task', 'completed' => false]);
    }

    public function test_store_requires_title(): void
    {
        $response = $this->post(route('todos.store'), ['title' => '']);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('todos', 0);
    }

    public function test_store_title_cannot_exceed_255_characters(): void
    {
        $response = $this->post(route('todos.store'), ['title' => str_repeat('a', 256)]);

        $response->assertSessionHasErrors('title');
    }

    public function test_toggle_marks_incomplete_todo_as_completed(): void
    {
        $todo = Todo::factory()->create(['completed' => false]);

        $response = $this->patch(route('todos.toggle', $todo));

        $response->assertRedirect(route('todos.index'));
        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'completed' => true]);
    }

    public function test_toggle_marks_completed_todo_as_incomplete(): void
    {
        $todo = Todo::factory()->create(['completed' => true]);

        $this->patch(route('todos.toggle', $todo));

        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'completed' => false]);
    }

    public function test_destroy_deletes_todo(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->delete(route('todos.destroy', $todo));

        $response->assertRedirect(route('todos.index'));
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_destroy_returns_404_for_nonexistent_todo(): void
    {
        $this->delete(route('todos.destroy', 9999))->assertStatus(404);
    }

    public function test_toggle_returns_404_for_nonexistent_todo(): void
    {
        $this->patch(route('todos.toggle', 9999))->assertStatus(404);
    }
}
