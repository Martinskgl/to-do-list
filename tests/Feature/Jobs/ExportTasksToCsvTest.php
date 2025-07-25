<?php

namespace App\Jobs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTasksToCsvTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_tasks_job_creates_csv()
    {
        $userId = 1;
        (new ExportTasksToCsv($userId))->handle();
        $file = storage_path("app/exports/tasks_{$userId}.csv");
        $this->assertFileExists($file);
    }

    public function test_export_tasks_job_creates_directory_if_not_exists()
    {
        $userId = 1;
        $dir = storage_path('app/exports');
        if (is_dir($dir)) {
            foreach (glob("$dir/*") as $file) {
                unlink($file);
            }
            rmdir($dir);
        }
        (new ExportTasksToCsv($userId))->handle();
        $this->assertDirectoryExists($dir);
    }
}
