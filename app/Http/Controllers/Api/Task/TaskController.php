<?php

namespace App\Http\Controllers\Api\Task;

use App\Filters\TaskFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\SearchTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Retrieve all user tasks
     *
     * @param \App\Http\Requests\Task\SearchTaskRequest $request
     * @param \App\Filters\TaskFilter $filter
     * @return \App\Http\Resources\Task\TaskCollection
     */
    public function index(SearchTaskRequest $request, TaskFilter $filter): TaskCollection
    {
        $currentUserId = Auth::id();

        $taskList = Task::query()
            ->filter($filter)
            ->sorted()
            ->where('user_id', $currentUserId)
            ->paginate();

        return TaskCollection::make($taskList);
    }

    /**
     * Create new task
     *
     * @param \App\Http\Requests\Task\StoreTaskRequest $request
     * @return \App\Http\Resources\Task\TaskResource
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $currentUser = Auth::user();

        $task = $currentUser->tasks()
            ->create($request->validated());

        return TaskResource::make($task);
    }

    /**
     * Get task details.
     *
     * @param \App\Models\Task\Task $task
     * @return \App\Http\Resources\Task\TaskResource
     */
    public function show(Task $task): TaskResource
    {
        return TaskResource::make($task);
    }

    /**
     * Update task.
     *
     * @param \App\Http\Requests\Task\UpdateTaskRequest $request
     * @param \App\Models\Task\Task $task
     * @return \App\Http\Resources\Task\TaskResource
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        return TaskResource::make($task);
    }

    /**
     * Remove task
     * @param \App\Models\Task\Task $task
     */
    public function destroy(Task $task): void
    {
        $task->delete();
    }
}
