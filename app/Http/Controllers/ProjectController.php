<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProjectMemberRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use App\Service\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected ProjectService $projectService) {}

    public function index(Request $request)
    {
        $projects = $this->projectService->getAll($request)->orderByDesc('created_at')->paginate(10);

        $userEmployees = User::with(['employee' => fn($query) => $query->orderBy('full_name')])
            ->where('role_id', '!=', 1)->get();

        return view('project.index', compact('projects', 'userEmployees'));
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
    public function store(StoreProjectRequest $request)
    {
        try {
            $projectData = $request->validated()['project'];
            $this->projectService->insert($projectData);
            return redirect()->back()->with('success', 'Tạo dự án thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('project.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $userEmployees = User::with(['employee' => fn($query) => $query->orderBy('full_name')])
            ->where('role_id', '!=', 1)->get();
        return view('project.edit', compact('project', 'userEmployees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $id)
    {
        try {
            $projectData = $request->validated()['project_edit'];
            $this->projectService->update($projectData, $id);
            return redirect()->back()->with('success', 'Cập nhật dự án thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->back()->with('success', 'Xóa dự án thành công');
    }

    public function addMember(AddProjectMemberRequest $request, string $id)
    {
        try {

            $this->projectService->insertMember($request->validated()['add_member'], $id);

            return redirect()->back()->with('success', 'Thêm nhân viên vào dự án thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
}
