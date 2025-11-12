<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Comment;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_comment_to_task()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['created_by' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/tasks/{$task->id}/comments", [
                'body' => 'This is a test comment.'
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', ['body' => 'This is a test comment.']);
    }

    /** @test */
    public function cannot_add_comment_without_authentication()
    {
        $task = Task::factory()->create();

        $response = $this->postJson("/api/tasks/{$task->id}/comments", [
            'body' => 'No auth comment',
        ]);

        $response->assertStatus(401);
    }
}
