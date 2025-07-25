<?php

namespace Tests\Feature\Http\Observers;

use App\Mail\TaskAssignMail;
use App\Mail\TaskCancelledMail;
use App\Mail\TaskCompletedMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TaskObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_assign_mail_sent_on_create_with_user_id()
    {
        Mail::fake();
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        Mail::assertSent(TaskAssignMail::class, function ($mail) use ($task, $user) {
            return $mail->task->id === $task->id && $mail->user->id === $user->id;
        });
    }

    public function test_completed_mail_sent_on_status_change()
    {
        Mail::fake();
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
        $task->status = 'completed';
        $task->save();
        Mail::assertSent(TaskCompletedMail::class, function ($mail) use ($task, $user) {
            return $mail->task->id === $task->id && $mail->user->id === $user->id;
        });
    }

    public function test_cancelled_mail_sent_on_status_change()
    {
        Mail::fake();
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
        $task->status = 'cancelled';
        $task->save();
        Mail::assertSent(TaskCancelledMail::class, function ($mail) use ($task, $user) {
            return $mail->task->id === $task->id && $mail->user->id === $user->id;
        });
    }
}
