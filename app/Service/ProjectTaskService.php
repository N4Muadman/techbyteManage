<?php

namespace App\Service;

use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Support\Facades\DB;

class ProjectTaskService
{
    public function insert($validatedData, $project_id)
    {
        DB::transaction(function () use ($validatedData, $project_id) {
            $project = Project::findOrFail($project_id);

            foreach ($validatedData['project_tasks'] as $data) {
                $memberIds = array_column(json_decode($data['task_members'], true), 'value');

                unset($data['task_members']);

                $task = $project->tasks()->create($data);

                $task->members()->attach($memberIds);
            }
        });
    }

    public function getTaskOfProject($id)
    {
        return Project::with('tasks.project.members')->findOrFail($id)->tasks;
    }

    public function updateTask($validatedData, $id)
    {
        DB::transaction(function () use ($validatedData, $id) {
            $task = ProjectTask::findOrFail($id);

            $status = match (true) {
                $validatedData['progress'] == 0 => 'pending',
                $validatedData['progress'] == 100 => 'completed',
                default => 'in_progress',
            };

            $task->update([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'progress' => $validatedData['progress'],
                'status' => $status,
                'due_date' => $validatedData['due_date'],
            ]);

            $memberIds = array_column(json_decode($validatedData['task_members'], true), 'value');
            $task->members()->sync($memberIds);
        });
    }

    public function deleteTask($id): bool
    {
        return DB::transaction(function () use ($id) {
            return ProjectTask::findOrFail($id)->delete();
        });
    }
}
