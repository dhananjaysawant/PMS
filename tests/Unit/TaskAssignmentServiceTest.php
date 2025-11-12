<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;
use App\Services\TaskAssignmentService;
use Exception;

class TaskAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskAssignmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskAssignmentService();
    }

    /** @test */
    public function it_assigns_task_to_a_valid_user()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'manager']);
        $task = Task::factory()->create(['assigned_to' => null]);

        // Act
        $result = $this->service->assign($task, $user);

        // Assert
        $this->assertEquals($user->id, $result->assigned_to);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'assigned_to' => $user->id,
        ]);
    }

    /** @test */
    public function it_throws_exception_if_user_is_admin()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);
        $task = Task::factory()->create();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Admin users cannot be assigned to tasks.');

        // Act
        $this->service->assign($task, $user);
    }
}
