<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewTaskModalLabel">
                <i class="bi bi-eye text-info me-2"></i>
                Chi tiết công việc
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row show-task {{ $task->status_class }}">
                <div class="col-md-8">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Tiêu đề</h6>
                        <h4 id="viewTaskTitle" class="mb-0 task-title">{{ $task->title }}</h4>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Mô tả chi tiết</h6>
                        <div id="viewTaskDescription" style="white-space: pre-line;"
                            class="border rounded p-3 bg-light">{{ $task->description }}</div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Tiến độ</h6>
                        <div class="d-flex align-items-center">
                            
                            <div class="progress flex-grow-1 me-3" style="height: 10px;">
                                <div id="viewTaskProgress" class="progress-bar task-progress-bar"
                                    style="width: {{ $task->progress }}%">
                                </div>
                            </div>
                            <span id="viewTaskProgressText"
                                class="fw-bold task-progress-text">{{ $task->progress }}%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Thành viên được giao</h6>
                        <div id="viewTaskMembers" class="mt-1">
                            @foreach ($task->members as $member)
                                @php
                                    $employee = $member->user?->employee;
                                    if (!$employee) {
                                        continue;
                                    }
                                @endphp
                                <div class="d-flex align-items-center">
                                    <div class="user-group me-2">
                                        <img src="{{ $employee->avatar ?? '/adminStatic/assets/images/user/avatar-1.jpg' }}"
                                            alt="user-image" class="avtar">
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $employee->full_name }}</div>
                                        <small class="text-muted">{{ $employee->position }}</small>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Thông tin công việc</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Trạng thái</small>
                                <div>
                                    <span id="viewTaskStatus" class="badge task-status">{{ $task->status_label }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Ngày tạo</small>
                                <div id="viewTaskCreated">{{ $task->created_at }}</div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Ngày hết hạn</small>
                                <div id="viewTaskDueDate" class="task-due-date">{{ $task->due_date }}</div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Quản lý dự án</small>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="user-group me-2">
                                        <img src="{{ $task->project->leader?->employee?->avatar ?? '/adminStatic/assets/images/user/avatar-1.jpg' }}"
                                            alt="user-image">
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $task->project->leader?->employee?->full_name }}
                                        </div>
                                        <small
                                            class="text-muted">{{ $task->project->leader?->employee?->position }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        </div>
    </div>
</div>
