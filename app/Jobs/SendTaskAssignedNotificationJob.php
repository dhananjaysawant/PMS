<?php
namespace App\Jobs;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskAssignedNotificationJob implements ShouldQueue {
    use InteractsWithQueue, Queueable, SerializesModels;
    public $task, $user;
    public function __construct(Task $task, User $user){ $this->task=$task; $this->user=$user; }
    public function handle(){
        $this->user->notify(new TaskAssignedNotification($this->task));
    }
}
