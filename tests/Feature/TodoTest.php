<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_todo_list_is_displayed(): void
    {
        $todo = Todo::create(['title' => 'Buy milk']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Buy milk')
            ->assertSee('TODO一覧（1件）');

        $this->assertFalse($todo->fresh()->completed);
    }

    public function test_todo_can_be_created_updated_completed_and_deleted(): void
    {
        $this->post('/todos', [
            'title' => 'Write tests',
            'description' => 'Cover the TODO flow',
        ])->assertRedirect('/');

        $todo = Todo::firstOrFail();
        $this->assertSame('Write tests', $todo->title);
        $this->assertFalse($todo->completed);

        $this->patch("/todos/{$todo->id}", [
            'title' => 'Write feature tests',
            'description' => 'Cover all actions',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Write feature tests',
            'description' => 'Cover all actions',
        ]);

        $this->patch("/todos/{$todo->id}/toggle")->assertRedirect('/');
        $this->assertTrue($todo->fresh()->completed);

        $this->delete("/todos/{$todo->id}")->assertRedirect('/');
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_todo_title_is_required(): void
    {
        $this->from('/')
            ->post('/todos', ['title' => ''])
            ->assertRedirect('/')
            ->assertSessionHasErrors('title');

        $this->assertDatabaseCount('todos', 0);
    }
}
