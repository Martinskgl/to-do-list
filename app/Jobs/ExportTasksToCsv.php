<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExportTasksToCsv implements ShouldQueue
{
    use Queueable;

    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tasks = Task::where('user_id', $this->userId)->get();
        \Log::debug('ExportTasksToCsv: handle chamado', ['userId' => $this->userId]);

        $dir = storage_path('app/exports');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $csv = fopen("{$dir}/tasks_{$this->userId}.csv", 'w');

        fputcsv($csv, ['id', 'title', 'describe', 'status', 'expiration_date']);

        foreach ($tasks as $task) {
            fputcsv($csv, [$task->id, $task->title, $task->describe, $task->status->value, $task->expiration_date]);
        }

        fclose($csv);
    }
}
