<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class TaskAssignmentService
{
    /**
     * Assign a task to a user.
     *
     * @param Task $task
     * @param User $user
     * @return Task
     * @throws Exception
     */
    public function assign(Task $task, User $user)
    {
        // Ensure the user exists
        if (!$user) {
            throw new Exception('User not found.');
        }

        // Admins cannot be assigned to tasks
        if ($user->role === 'admin') {
            throw new Exception('Admin users cannot be assigned to tasks.');
        }

        // Assign task
        $task->assigned_to = $user->id;
        $task->save();

        Log::info("Task ID {$task->id} assigned to user {$user->id}");

        return $task;
    }
}
