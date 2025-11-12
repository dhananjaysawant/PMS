<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;

class ProjectFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/projects', [
                'title' => 'New Project',
                'description' => 'Test project',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', ['title' => 'New Project']);
    }

    /** @test */
    public function non_admin_cannot_create_project()
    {
        $user = User::factory()->create(['role' => 'manager']);
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/projects', ['title' => 'Blocked Project']);

        $response->assertStatus(403);
    }

    /** @test */
    public function can_list_all_projects()
    {
        Project::factory()->count(3)->create();
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/projects');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
}
