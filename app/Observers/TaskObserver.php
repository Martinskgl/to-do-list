<?php

namespace App\Observers;

use App\Mail\TaskAssignMail;
use App\Mail\TaskCancelledMail;
use App\Mail\TaskCompletedMail;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;

class TaskObserver
{
    public function updated(Task $task)
    {
        if ($task->isDirty('user_id')) {
            Mail::to($task->user->email)->send(new TaskAssignMail($task, $task->user));
        }

        if ($task->isDirty('status')) {
            if ($task->status->value === 'completed') {
                Mail::to($task->user->email)->send(new TaskCompletedMail($task, $task->user));
            }
            if ($task->status->value === 'cancelled') {
                Mail::to($task->user->email)->send(new TaskCancelledMail($task, $task->user));
            }
        }
    }

    public function created(Task $task)
    {
        if ($task->isDirty('user_id')) {
            Mail::to($task->user->email)->send(new TaskAssignMail($task, $task->user));
        }
    }
}
