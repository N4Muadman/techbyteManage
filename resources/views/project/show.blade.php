<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewTaskModalLabel">
                <i class="bi bi-eye text-info me-2"></i>
                Chi tiết dự án
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row show-task {{ $project->status_class_label }}">
                <div class="col-md-8">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Tên dự án</h6>
                        <h4 id="viewTaskTitle" class="mb-0 task-title">{{ $project->name }}</h4>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Mô tả chi tiết</h6>
                        <div id="viewTaskDescription" style="white-space: pre-line;"
                            class="border rounded p-3 bg-light">{{ $project->description }}</div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Tiến độ</h6>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-3" style="height: 10px;">
                                <div id="viewTaskProgress" class="progress-bar task-progress-bar"
                                    style="width: {{ $project->progress }}%">
                                </div>
                            </div>
                            <span id="viewTaskProgressText"
                                class="fw-bold task-progress-text">{{ $project->progress }}%</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Quản lý dự án</h6>
                        <div class="d-flex align-items-center mt-1">
                            <div class="user-group me-2">
                                <img src="{{ $project->leader?->employee?->avatar ?? '/adminStatic/assets/images/user/avatar-1.jpg' }}"
                                    alt="user-image">
                            </div>
                            <div>
                                <div class="fw-medium">{{ $project->leader?->employee?->full_name }}
                                </div>
                                <small class="text-muted">{{ $project->leader?->employee?->position }}</small>
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Thành viên trong dự án</h6>
                        <div id="viewTaskMembers" class="mt-1">
                            @foreach ($project->members as $member)
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
                            <h6 class="mb-0">Thông tin dự án</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="text-muted m-0">Loại dự án</p>
                                <div id="viewTaskCreated">{{ $project->type_label }}</div>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted m-0">Tổng chi phí dự án</p>
                                <div id="viewTaskCreated">{{ number_format($project->total_cost) }} VNĐ</div>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted m-0">Trạng thái</p>
                                <div>
                                    <span id="viewTaskStatus"
                                        class="badge task-status">{{ $project->status_label }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted m-0">Ngày tạo</p>
                                <div id="viewTaskCreated">{{ $project->start_date }}</div>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted m-0">Ngày hết hạn</p>
                                <div id="viewTaskDueDate" class="task-due-date">{{ $project->end_date }}</div>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted m-0">Link lưu trữ</p>
                                <div id="viewTaskDueDate">
                                    <a href="{{ $project->archive_link }}" target="_blank"
                                        rel="noopener noreferrer">{{ $project->archive_link }}</a>
                                </div>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted m-0">Link tài liệu</p>
                                <div id="viewTaskDueDate">
                                    <a href="{{ $project->document_link }}" target="_blank"
                                        rel="noopener noreferrer">{{ $project->document_link }}</a>
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
