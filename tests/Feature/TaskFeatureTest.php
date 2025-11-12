<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class TaskFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function manager_can_create_task()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $token = $manager->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/projects/{$project->id}/tasks", [
                'title' => 'Task 1',
                'description' => 'Testing task',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['title' => 'Task 1']);
    }

    /** @test */
    public function assigned_user_can_update_own_task()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $user = User::factory()->create(['role' => 'user']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'assigned_to' => $user->id]);
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson("/api/tasks/{$task->id}", [
                'status' => 'in-progress'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'in-progress']);
    }
}
