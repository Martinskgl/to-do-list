<?php

namespace Tests\Feature\Http\Enums;

use App\Enums\TaskStatus;
use PHPUnit\Framework\TestCase;

class TaskStatusTest extends TestCase
{
    public function test_has_correct_values()
    {
        $this->assertEquals('pending', TaskStatus::PENDING->value);
        $this->assertEquals('in_progress', TaskStatus::IN_PROGRESS->value);
        $this->assertEquals('completed', TaskStatus::COMPLETED->value);
        $this->assertEquals('cancelled', TaskStatus::CANCELLED->value);
    }

    public function test_can_create_from_string()
    {
        $this->assertEquals(TaskStatus::PENDING, TaskStatus::from('pending'));
        $this->assertEquals(TaskStatus::COMPLETED, TaskStatus::from('completed'));
    }

    public function test_try_from_returns_null_for_invalid()
    {
        $this->assertNull(TaskStatus::tryFrom('invalid'));
        $this->assertEquals(TaskStatus::PENDING, TaskStatus::tryFrom('pending'));
    }

    public function test_from_throws_exception_for_invalid()
    {
        $this->expectException(\ValueError::class);
        TaskStatus::from('invalid');
    }

    public function test_has_four_cases()
    {
        $this->assertCount(4, TaskStatus::cases());
    }

    public function test_can_be_compared()
    {
        $this->assertTrue(TaskStatus::PENDING === TaskStatus::PENDING);
        $this->assertFalse(TaskStatus::PENDING === TaskStatus::COMPLETED);
    }
}