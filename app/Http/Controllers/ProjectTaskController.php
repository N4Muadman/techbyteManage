<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectTaskRequest;
use App\Http\Requests\UpdateProjectTaskRequest;
use App\Models\ProjectTask;
use App\Service\ProjectTaskService;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(protected ProjectTaskService $project_task_service)
    {
        
    }
    public function index($id)
    {
        $tasks = $this->project_task_service->getTaskOfProject($id);

        return view('project.task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectTaskRequest $request, $id)
    {
        try{
            $this->project_task_service->insert($request->validated(), $id);
            
            return redirect()->back()->with('success', 'Thêm công việc của dự án thành công');
        }catch(\Exception $e){
            return redirect()->back()
                ->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectTask $task)
    {
        return view('project.task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectTask $task)
    {
        return view('project.task.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectTaskRequest $request, string $id)
    {
        try{
            $this->project_task_service->updateTask($request->validated(), $id);            
            return redirect()->back()->with('success', 'Cập nhật công việc của dự án thành công');
        }catch(\Exception $e){
            return redirect()->back()
                ->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         try{
            $this->project_task_service->deleteTask($id);            
            return redirect()->back()->with('success', 'Xóa công việc của dự án thành công');
        }catch(\Exception $e){
            return redirect()->back()
                ->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
}
