<?php

namespace Database\Factories;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\User;
use App\Models\task;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CommentFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(){
        return [
            'body'=>$this->faker->sentence,
            'task_id'=>Task::factory(),
            'user_id'=>User::factory()
        ];
    }
    
    

    /**
     * Indicate that the model's email address should be unverified.
     */
    
}
