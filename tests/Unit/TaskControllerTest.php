<?php

namespace Tests\Unit;

use App\Models\Task\Enum\TaskPriorityEnum;
use App\Models\Task\Enum\TaskStatusEnum;
use App\Models\Task\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreNewTask()
    {
        // Arrange
        $user = User::factory()->create();
        $this->be($user);
        $defaultStatus = TaskStatusEnum::WAITING->name;
        $defaultPriority = TaskPriorityEnum::LOW->name;

        $data = [
            'title' => 'New Task',
            'description' => 'Task description',
        ];

        // Act
        $response = $this->postJson('api/tasks', $data);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Task',
                    'description' => 'Task description',
                    'status' => $defaultStatus,
                    'priority' => $defaultPriority,
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $user->id,
        ]);
    }

    public function testStoreNewTaskWithAllData()
    {
        // Arrange
        $user = User::factory()->create();
        $this->be($user);

        $requestStatus = TaskStatusEnum::IN_PROGRESS->value;
        $requestPriority = TaskPriorityEnum::HIGH->value;
        $requestDueDate = Carbon::now()->format('Y-m-d\TH:i:s.000000\Z');

        $data = [
            'title' => 'New Task',
            'description' => 'Task description',
            'status' => $requestStatus,
            'priority' => $requestPriority,
            'due_date' => $requestDueDate
        ];

        // Act
        $response = $this->postJson('api/tasks', $data);

        // Assert

        $expectedStatusName = TaskStatusEnum::IN_PROGRESS->name;
        $expectedPriorityName = TaskPriorityEnum::HIGH->name;
        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Task',
                    'description' => 'Task description',
                    'status' => $expectedStatusName,
                    'priority' => $expectedPriorityName,
                    'due_date' => $requestDueDate
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'description' => 'Task description',
            'status' => $requestStatus,
            'priority' => $requestPriority,
            'due_date' => $requestDueDate,
            'user_id' => $user->id,
        ]);
    }

    public function testUpdateTaskName()
    {
        // Arrange
        $user = User::factory()->create();
        $this->be($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $newTaskName = 'Updated Task';
        $data = [
            'title' => $newTaskName,
        ];

        // Act
        $response = $this->putJson('api/tasks/' . $task->id, $data);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => $newTaskName,
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $newTaskName,
            'user_id' => $user->id,
            'id' => $task->id,
        ]);
    }


    public function testDeleteTask()
    {
        // Arrange
        $user = User::factory()->create();
        $this->be($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        // Act
        $response = $this->deleteJson('api/tasks/' . $task->id);

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'user_id' => $user->id,
        ]);
    }
}
