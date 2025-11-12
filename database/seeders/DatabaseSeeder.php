<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Comment;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //3 admin 
        User::factory()->count(3)->state(['role'=>'admin'])->create();
    // 3 managers
    User::factory()->count(3)->state(['role'=>'manager'])->create();
    // 5 users
    User::factory()->count(5)->create();


    // create 5 projects assigned to random admin as 'created_by'
    $admins = User::where('role','admin')->get();
    Project::factory()->count(5)->make()->each(function($proj) use ($admins){
        $proj->created_by = $admins->random()->id;
        $proj->save();
    });

    // 10 tasks (random projects)
    $projects = Project::all();
    Task::factory()->count(10)->make()->each(function($task) use ($projects){
        $task->project_id = $projects->random()->id;
        // optionally assign to random manager or null
        $manager = User::where('role','manager')->inRandomOrder()->first();
        $task->assigned_to = $manager?->id;
        $task->save();
    });

    // 10 comments
    $tasks = Task::all();
    Comment::factory()->count(10)->make()->each(function($c) use ($tasks){
        $c->task_id = $tasks->random()->id;
        $c->user_id = User::inRandomOrder()->first()->id;
        $c->save();
    });
    }
}
