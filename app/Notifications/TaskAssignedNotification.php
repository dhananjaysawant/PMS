<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;

class TaskAssignedNotification extends Notification {
    use Queueable;
    protected $task;
    public function __construct(Task $task){ $this->task = $task; }

    public function via($notifiable){ return ['mail']; }

    public function toMail($notifiable){
        return (new MailMessage)
            ->subject('New Task Assigned: '.$this->task->title)
            ->line('A new task has been assigned to you.')
            ->action('View Task', url('/tasks/'.$this->task->id))
            ->line('Due date: '.$this->task->due_date);
    }
}
