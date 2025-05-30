@php
    $taskGroups = $tasks->groupBy(function ($task) {
        if ($task->due_date < today() && $task->status != 'completed') {
            return 'overdue';
        }
        return $task->status;
    });
    $project = $tasks?->first()?->project;
    $members = $project?->members?->map(
        fn($m) => [
            'value' => $m->id,
            'name' => $m->user?->employee?->full_name,
        ],
    );
@endphp

<div class="modal fade" id="show-project-task" tabindex="-1" aria-labelledby="addLeaveModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title mb-0" id="tasksModalLabel">
                        <i class="bi bi-list-task text-primary me-2"></i>
                        Danh sách công việc
                    </h5>
                    <small class="text-muted ms-2 fs-6">Dự án: {{ $project?->name }}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Actions bar -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">

                        <span class="text-muted">Tổng: <strong>{{ $tasks->count() }} công việc</strong></span>
                        <span class="badge bg-secondary">
                            {{ $taskGroups->get('pending', collect())->count() }} Chưa làm
                        </span>

                        <span class="badge bg-warning">
                            {{ $taskGroups->get('in_progress', collect())->count() }} đang làm
                        </span>

                        <span class="badge bg-success">
                            {{ $taskGroups->get('completed', collect())->count() }} hoàn thành
                        </span>

                        <span class="badge bg-danger">
                            {{ $taskGroups->get('overdue', collect())->count() }} quá hạn
                        </span>
                    </div>
                    @if (Auth::user()->hasPermissionOnPage('2', '12') && Auth::user()->is_project_leader($project->leader_id))
                        <button type="button" class="btn btn-success btn-add-project-task" data-bs-toggle="modal"
                            data-bs-target="#add-project-task" data-members="{{ $members }}"
                            data-id="{{ $project?->id }}">
                            <i class="ti ti-plus me-1"></i>
                            Thêm công việc
                        </button>
                    @endif
                </div>


                <!-- Danh sách công việc -->
                <div class="tasks-container" style="max-height: 500px; overflow-y: auto;">

                    @forelse ($tasks as $task)
                        <div class="task-item p-3 {{ $task->status_class }}">
                            <div class="row align-items-start">
                                <div class="col">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <div class="task-title">{{ $task->title }}</div>
                                            <div class="task-description mb-2">
                                                {{ $task->description }}
                                            </div>
                                            <div class="progress-bar-container mb-2">
                                                <div class="progress-bar-fill" style="width: {{ $task->progress }}%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="task-actions ms-2">
                                            @if (Auth::user()->hasPermissionOnPage('3', '12'))
                                                <button class="action-btn" data-id="{{ $task->id }}"
                                                    data-action="show" title="Xem chi tiết">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            @endif
                                            @if (Auth::user()->hasPermissionOnPage('2', '12') && Auth::user()->is_project_leader($project->leader_id))
                                                <button class="action-btn" data-id="{{ $task->id }}"
                                                    data-members="{{ $members }}"
                                                    data-task-members="{{ $task->members->map(fn($m) => ['value' => $m->id, 'name' => $m->user?->employee?->full_name]) }}"
                                                    data-action="edit" title="Sửa">
                                                    <i class="ti ti-pencil"></i>
                                                </button>

                                                <button class="action-btn text-danger" data-id="{{ $task->id }}"
                                                    data-title="{{ $task->title }}" data-action="delete"
                                                    title="Xóa">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center task-meta">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <span class="due-date">{{ $task->due_date }}</span>
                                            <span class="badge bg-secondary ms-2">{{ $task->status_label }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 text-muted">Thành viên:</span>
                                            <div class="user-group able-user-group">
                                                @foreach ($task->members as $member)
                                                    <img src="{{ $member->user?->employee?->avatar ?? '/adminStatic/assets/images/user/avatar-1.jpg' }}"
                                                        alt="user-image" class="avtar" data-bs-toggle="tooltip"
                                                        title="{{ $member->user?->employee?->full_name }}">
                                                @endforeach
                                            </div>
                                            {{-- @foreach ($task->members as $member)
                                                <div class="member-avatar">A</div>
                                            @endforeach --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">Không có công việc nào</p>
                    @endforelse
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-light">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Cập nhật lần cuối: {{ $tasks->sortByDesc('updated_at')?->first()?->updated_at }}
                    </small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>
