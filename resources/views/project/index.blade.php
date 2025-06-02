@extends('layout')

@section('content')
    @php
        use Carbon\Carbon;
        $now = Carbon::now();
        $user = Auth::user();
    @endphp
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quản lý dự án</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Danh sách dự án</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách dự án</h2>
                        @auth
                            @if ($user->hasPermissionOnPage('1', '12'))
                                <button data-bs-toggle="modal" data-bs-target="#add-project"
                                    class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i>
                                    Thêm mới dự án</button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('projects.index') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 col-sm-2 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo dự án" value="{{ request('name') }}"
                    name="name" id="">
            </div>
            <div class="col-6 col-sm-2 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm tên leader" value="{{ request('leader') }}"
                    name="leader" id="">
            </div>
            <div class="col-6 col-sm-2 mb-2">
                <input type="text" class="form-control" placeholder="Tên thành viên" value="{{ request('member') }}"
                    name="member" id="">
            </div>
            <div class="col-6 col-sm-2">
                <button type="submit" class="btn btn-info">Tìm kiếm</button>
            </div>
        </div>
    </form>
    <div class="row">
        @forelse ($projects as $project)
            <div class="col-xl-4 col-md-6">
                <div class="card ">
                    <div class="card-body {{ $project->status_class_label }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">{{ $project->name }}</h5>
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ti ti-dots-vertical f-18"></i></a>
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    @if ($user->hasPermissionOnPage('3', '12'))
                                        <a class="dropdown-item" data-id="{{ $project->id }}" data-action="show"
                                            href="#!">Xem</a>
                                    @endif
                                    @if ($user->hasPermissionOnPage('2', '12') && $user->is_project_leader($project->leader_id))
                                        <a class="dropdown-item" data-leader="{{ $project->leader_id }}"
                                            data-members="{{ $project->members->map(fn($m) => ['value' => $m->user_id, 'name' => $m->user?->employee?->full_name]) }}"
                                            data-id="{{ $project->id }}" data-action="edit" href="#!">Sửa</a>
                                    @endif
                                    @if ($user->hasPermissionOnPage('4', '12') && $user->is_project_leader($project->leader_id))
                                        <a class="dropdown-item" data-id="{{ $project->id }}"
                                            data-name="{{ $project->name }}" data-action="delete" href="#!">Xóa</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="mb-2">{{ $project->description }} </p>
                        </div>

                        @if (!$project->tasks->isEmpty())
                            <div class="d-flex align-items-center my-3">
                                <div class="flex-shrink-0">
                                    <div class="avtar avtar-s bg-light-success btn-show-task cursor-pointer"
                                        data-id="{{ $project->id }}"><i class="ti ti-list-check f-20"></i></div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">
                                        Đầu mục công việc
                                        <span style="padding: 3px 8px"
                                            class="bg-light-secondary rounded-pill">{{ $project->tasks->count() }}</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="my-3">
                                <p class="mb-2">Tiến độ <span class="float-end">{{ $project->progress }}%</span></p>
                                <div class="progress" style="height: 8px">
                                    <div class="progress-bar progress-bar-fill" style="width: {{ $project->progress }}%">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="my-3">
                            <h6 class="mb-1">Thời gian thực hiện:</h6>
                            <p class="mb-2 due-date">{{ $project->start_date . ' đến ' . $project->end_date }}</p>

                            <h6 class="mb-1 d-inline">Trạng thái:</h6> <span
                                class="badge">{{ $project->status_label }}</span>
                        </div>

                        <div class="my-3">
                            <h6 class="mb-2">Quản lý dự án </h6>
                            <div class="d-flex align-items-center justify-content-between">
                                @if ($project->leader)
                                    <div class="user-group able-user-group">
                                        <img src="{{ $project->leader?->employee?->avatar ?? '/adminStatic/assets/images/user/avatar-1.jpg' }}"
                                            alt="user-image" title="{{ $project->leader?->employee?->full_name }}"
                                            class="avtar" data-bs-toggle="tooltip">
                                        {{-- <span class="avtar bg-light-primary text-primary text-sm">+2</span> --}}
                                    </div>
                                @else
                                    <div class="user-group able-user-group"></div>
                                    <a href="#" class="avtar avtar-s btn btn-primary rounded-circle">
                                        <i class="ti ti-plus f-20"></i>
                                    </a>
                                @endif


                            </div>
                        </div>
                        <div class="my-3">
                            <h6 class="mb-2">Thành viên </h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="user-group able-user-group">

                                    @foreach ($project->members as $member)
                                        <img src="{{ $member->user?->employee?->avatar ?? '/adminStatic/assets/images/user/avatar-1.jpg' }}"
                                            alt="user-image" class="avtar"
                                            title="{{ $member->user?->employee?->full_name }}" data-bs-toggle="tooltip">
                                    @endforeach
                                    {{-- <span class="avtar bg-light-primary text-primary text-sm">+2</span> --}}
                                </div>
                                @if ($user->hasPermissionOnPage('2', '12') && $user->is_project_leader($project->leader_id))
                                    <a href="#!" data-bs-toggle="modal" data-bs-target="#add-project-member"
                                        data-name="{{ $project->name }}" data-id="{{ $project->id }}"
                                        data-leader="{{ $project->leader_id }}"
                                        data-members='@json($project->members->map(fn($m) => "$m->user_id"))'
                                        class="avtar btn-add-member btn btn-primary rounded-circle">
                                        <i class="ti ti-plus f-20"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if (
                            $project->tasks->isEmpty() &&
                                $user->hasPermissionOnPage('2', '12') &&
                                $user->is_project_leader($project->leader_id))
                            <div class="d-grid mt-3">
                                <button data-bs-toggle="modal" data-bs-target="#add-project-task"
                                    data-id="{{ $project->id }}" data-members='@json($project->members->map(fn($m) => ['value' => $m->id, 'name' => $m->user?->employee?->full_name]))'
                                    class="btn btn-primary btn-add-project-task d-flex align-items-center justify-content-center">
                                    <i class="ti ti-plus"></i> Thêm đầu mục công việc
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center mt-5">Không có dự án nào</p>
        @endforelse
    </div>

    <div class="modal fade" id="add-project" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title d-flex align-items-center" id="addProjectModalLabel">
                        <i class="bi bi-rocket-takeoff me-3 fs-2"></i>
                        Tạo Dự Án Mới
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="addProjectForm" action="{{ route('projects.store') }}" method="post">
                        @csrf
                        <!-- Thông tin cơ bản -->
                        <div class="section-card fade-in">
                            <div class="section-title">
                                Thông Tin Dự Án
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-folder me-1"></i>
                                        Tên dự án <span class="required-mark">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @error('project.name') border-danger @enderror"
                                            placeholder="Nhập tên dự án" name="project[name]"
                                            value="{{ old('project.name') }}" required>
                                        <i class="ti ti-pencil input-icon"></i>

                                    </div>
                                    @error('project.name')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-tag me-1"></i>
                                        Loại dự án <span class="required-mark">*</span>
                                    </label>
                                    <select class="form-select @error('project.type') border-danger @enderror"
                                        name="project[type]" required>
                                        <option value="">🎯 Chọn loại dự án</option>
                                        <option value="internal"
                                            {{ old('project.type') == 'internal' ? 'selected' : '' }}>🏢 Nội bộ</option>
                                        <option value="customer"
                                            {{ old('project.type') == 'customer' ? 'selected' : '' }}>👥 Khách hàng
                                        </option>
                                    </select>
                                    @error('project.type')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-calendar-event me-1"></i>
                                        Ngày bắt đầu <span class="required-mark">*</span>
                                    </label>
                                    <input type="date"
                                        class="form-control @error('project.start_date') border-danger @enderror"
                                        name="project[start_date]" value="{{ old('project.start_date') }}" required>
                                    @error('project.start_date')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror

                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-calendar-check me-1"></i>
                                        Ngày kết thúc <span class="required-mark">*</span>
                                    </label>
                                    <input type="date"
                                        class="form-control @error('project.end_date') border-danger @enderror"
                                        name="project[end_date]" value="{{ old('project.end_date') }}" required>
                                    @error('project.end_date')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tài chính & Nhân sự -->
                        <div class="section-card fade-in" style="animation-delay: 0.1s">
                            <div class="section-title">
                                Tài Chính & Nhân Sự
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-cash-stack me-1"></i>
                                        Tổng chi phí
                                    </label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control money @error('project.total_cost') border-danger @enderror"
                                            name="project[total_cost]" value="{{ old('project.total_cost') }}"
                                            placeholder="0 VNĐ">
                                    </div>
                                    @error('project.total_cost')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-person-badge me-1"></i>
                                        Quản lý dự án <span class="required-mark">*</span>
                                    </label>
                                    <select class="form-select @error('project.leader_id') border-danger @enderror"
                                        name="project[leader_id]" required>
                                        <option value="">👤 Chọn quản lý dự án</option>
                                        @foreach ($userEmployees as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('project.leader_id') == $user->id ? 'selected' : '' }}>👨‍💼
                                                {{ $user->employee?->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project.leader_id')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="ti ti-people me-1"></i>
                                        Thành viên dự án <span class="required-mark">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input name="project[project_members]"
                                            class="form-control @error('project.project_members') border-danger @enderror"
                                            placeholder="Chọn thành viên dự án">

                                    </div>
                                    @error('project.project_members')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Liên kết -->
                        <div class="section-card fade-in" style="animation-delay: 0.2s">
                            <div class="section-title">
                                Liên Kết & Tài Liệu
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-cloud-arrow-up me-1"></i>
                                        Link lưu trữ
                                    </label>
                                    <div class="input-group">
                                        <input type="url" placeholder="https://drive.google.com/..."
                                            class="form-control @error('project.archive_link') border-danger @enderror"
                                            name="project[archive_link]" value="{{ old('project.archive_link') }}">

                                    </div>
                                    @error('project.archive_link')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-file-earmark-text me-1"></i>
                                        Link tài liệu
                                    </label>
                                    <div class="input-group">
                                        <input type="url" placeholder="https://docs.google.com/..."
                                            class="form-control @error('project.document_link') border-danger @enderror"
                                            name="project[document_link]" value="{{ old('project.document_link') }}">
                                    </div>
                                    @error('project.document_link')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả -->
                        <div class="section-card fade-in" style="animation-delay: 0.3s">
                            <div class="section-title">
                                Mô Tả Chi Tiết
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="ti ti-textarea-resize me-1"></i>
                                        Mô tả dự án
                                    </label>
                                    <textarea name="project[description]" placeholder="Mô tả chi tiết về dự án, mục tiêu, yêu cầu và ghi chú khác..."
                                        class="form-control @error('project.description') border-danger @enderror" rows="3">{{ old('project.description') }}</textarea>
                                    @error('project.description')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Tạo Dự Án
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="show-edit-project" tabindex="-1" aria-labelledby="addLeaveModalModalLabel"
        aria-hidden="true">

    </div>

    <div class="modal fade" id="delete-project" tabindex="-1" aria-labelledby="addLeaveModalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Xác nhận xóa dự án
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h6>Bạn có chắc chắn muốn xóa dự án này?</h6>
                        <p class="text-muted mb-0">
                            <strong id="deleteProjectName"></strong>
                        </p>
                        <p class="text-muted small">Hành động này không thể hoàn tác!</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>
                        Hủy
                    </button>
                    <form id="form-delete-project" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>
                            Xóa dự án
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add-project-member" tabindex="-1" aria-labelledby="addLeaveModalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addLeaveModalModalLabel">Thêm thành viên vào dự án</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-add-project-member" method="post">
                        @csrf
                        <div class="col-12 col-md-12 mb-3">
                            <h3 class="text-center">Dự án: <span id="name-project-add-member"></span></h3>
                        </div>
                        <div class="card task-item">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">Thành viên dự án <span
                                                class="text-danger">(*)</span></label>
                                        <input name="add_member[project_members]"
                                            class="form-control @error('add_member.project_members') border-danger @enderror"
                                            placeholder="Chọn thành viên dự án">
                                        @error('add_member.project_members')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end"><button type="submit" class="btn btn-primary">Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-project-task" tabindex="-1" aria-labelledby="addLeaveModalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addLeaveModalModalLabel">Thêm đầu mục công việc</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-add-project-task" method="post">
                        @csrf
                        <div id="task-wrapper">
                        </div>

                        <div class="col-12 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="btn-add-task">Thêm dòng</button>
                            <button type="submit" class="btn btn-primary">Lưu tất cả</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xem danh sách công việc của từng dự án -->
    <div id="content-list-task">

    </div>

    <!-- Modal xem chi tiết công việc -->
    <div class="modal fade" id="actionTaskModal" tabindex="-1" aria-labelledby="actionTaskModalLabel"
        aria-hidden="true">

    </div>

    <!-- Modal xác nhận xóa công việc -->
    <div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteTaskModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Xác nhận xóa công việc
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h6>Bạn có chắc chắn muốn xóa công việc này?</h6>
                        <p class="text-muted mb-0">
                            <strong id="deleteTaskTitle"></strong>
                        </p>
                        <p class="text-muted small">Hành động này không thể hoàn tác!</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>
                        Hủy
                    </button>
                    <form id="form-delete-task" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>
                            Xóa công việc
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="message-toast">

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let tagifyInstances = {};
            const wrapper = document.getElementById('task-wrapper');
            const btnAdd = document.getElementById('btn-add-task');
            let projectMember = [];

            var userEmployees = [
                @foreach ($userEmployees as $user)
                    {
                        value: "{{ $user->id }}",
                        name: "{{ $user->employee?->full_name }}"
                    },
                @endforeach
            ];

            document.querySelectorAll('.dropdown-item').forEach(element => {
                element.addEventListener('click', async () => {
                    const id = element.dataset.id;
                    const action = element.dataset.action;

                    switch (action) {
                        case 'show':
                            showProject(id);
                            break;

                        case 'edit':
                            const members = JSON.parse(element.dataset.members || "[]");
                            const filteredUsers = userEmployees.filter(user => user.value !=
                                element.dataset.leader);

                            editProject(id, filteredUsers, members);
                            break;
                        case 'delete':
                            document.getElementById('deleteProjectName').innerText = element
                                .dataset.name;
                            document.getElementById('form-delete-project').action =
                                '{{ route('projects.destroy', ':id') }}'.replace(':id', element
                                    .dataset.id);
                            new bootstrap.Modal(document.getElementById('delete-project'))
                                .show();
                            break;
                    }

                });
            });

            document.querySelectorAll('.btn-show-task').forEach(element => {
                element.addEventListener('click', async () => {
                    const id = element.dataset.id;
                    if (id) {
                        await getTaskOfProject(id);

                        document.querySelectorAll('.btn-add-project-task').forEach(element => {
                            element.addEventListener('click', () => {
                                addTaskWrapper();
                                setProjectMemberFromElement(element);
                            })
                        });

                        document.querySelectorAll('.action-btn').forEach(btn => {
                            btn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                const action = this.dataset.action;

                                switch (action) {
                                    case 'show':
                                        showTaskById(this.dataset.id)
                                        break;
                                    case 'edit':
                                        projectMember = JSON.parse(this.dataset
                                            .members || "[]");
                                        const taskMembers = JSON.parse(this
                                            .dataset.taskMembers || "[]");
                                        editTask(this.dataset.id, projectMember,
                                            taskMembers);

                                        break;
                                    case 'delete':
                                        document.getElementById(
                                                'deleteTaskTitle').innerText =
                                            this.dataset.title;
                                        document.getElementById(
                                                'form-delete-task').action =
                                            '{{ route('project_tasks.destroy', ':id') }}'
                                            .replace(':id', this.dataset.id);
                                        new bootstrap.Modal(document
                                            .getElementById(
                                                'deleteTaskModal')).show();
                                        break;
                                }
                            });
                        });



                    }
                });
            });

            btnAdd?.addEventListener('click', function() {
                const taskItems = wrapper.querySelectorAll('.task-item');
                const clone = taskItems[0].cloneNode(true);

                clone.querySelector('.tagify')?.remove();

                clone.querySelectorAll('input, textarea').forEach(input => input.value = '');
                wrapper.appendChild(clone);

                reIndexTasks();
            });

            wrapper?.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-task')) {
                    const allTasks = wrapper.querySelectorAll('.task-item');
                    if (allTasks.length > 1) {
                        e.target.closest('.task-item').remove();
                        reIndexTasks();
                        showToast('Xóa công việc thành công!', 'bg-success');
                    } else {
                        alert('Phải có ít nhất 1 công việc!');
                    }
                }
            });

            document.querySelectorAll('.btn-add-member').forEach(element => {
                element.addEventListener('click', () => {
                    const id = element.dataset.id;
                    const employee = JSON.parse(element.dataset.members || "[]");
                    const filteredUsers = userEmployees.filter(user =>
                        !employee.includes(user.value) && user.value != element.dataset.leader
                    );

                    if (id) {
                        document.getElementById('form-add-project-member').action =
                            '{{ route('projects.add-member', ':id') }}'.replace(':id', id);
                    }

                    document.getElementById('name-project-add-member').innerText = element.dataset
                        .name;

                    initTagifyForInput('input[name="add_member[project_members]"]', filteredUsers,
                        true);
                });
            });

            initTagifyForInput('input[name="project[project_members]"]', userEmployees);

            document.querySelectorAll('.btn-add-project-task').forEach(element => {
                element.addEventListener('click', () => {
                    addTaskWrapper();
                    setProjectMemberFromElement(element);
                })
            })

            function addTaskWrapper() {
                document.getElementById('task-wrapper').innerHTML = `@include('project.task.add')`;

            }

            function setProjectMemberFromElement(element) {
                try {
                    projectMember = JSON.parse(element.dataset.members || "[]");

                    initTagifyForInput('input[name="project_tasks[0][task_members]"]', projectMember);
                } catch (e) {
                    console.warn("Không thể parse danh sách nhân viên");
                }

                const id = element.dataset.id;
                if (id) {
                    document.getElementById('form-add-project-task').action =
                        '{{ route('project_tasks.store', ':id') }}'.replace(':id', id);
                }
            }

            function initTagifyForInput(selector, data, destroy = false, preselected = []) {
                const input = document.querySelector(selector);
                if (!input) return;

                if (destroy && tagifyInstances[selector]) {
                    tagifyInstances[selector].destroy();
                }

                input.value = JSON.stringify(preselected);

                const tagify = new Tagify(input, {
                    whitelist: data,
                    dropdown: {
                        maxItems: 20,
                        enabled: 0,
                        closeOnSelect: false,
                        position: "all",
                        mapValueTo: "name",
                        searchKeys: ["name"]
                    },
                    enforceWhitelist: true,
                    tagTextProp: "name",
                    duplicates: false,
                    placeholder: "Chọn nhiều thành viên dự án",
                    maxTags: Infinity,
                    templates: {
                        dropdownItem: function(tagData) {
                            return `<div class='tagify__dropdown__item' value="${tagData.value}">
                                <span>${tagData.name}</span>
                            </div>`;
                        }
                    }
                });

                if (destroy) {
                    tagifyInstances[selector] = tagify;
                }
            }

            function reIndexTasks() {
                const taskItems = wrapper.querySelectorAll('.task-item');
                taskItems.forEach((task, index) => {
                    task.setAttribute('data-index', index);
                    task.querySelector('.task-number').textContent = index + 1;

                    task.querySelectorAll('input, textarea').forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/project_tasks\[\d+\]/,
                                `project_tasks[${index}]`);
                            input.setAttribute('name', newName);
                        }

                        if (input.name.includes('[task_members]')) {
                            initTagifyForInput(
                                `input[name="project_tasks[${index}][task_members]"]`,
                                projectMember);
                        }
                    });
                });
            }

            async function getTaskOfProject(id) {
                const response = await fetch('{{ route('project_tasks.index', ':id') }}'.replace(':id', id));
                if (!response.ok) return showToast('Có lỗi xảy ra!', 'bg-error');

                document.getElementById('content-list-task').innerHTML = await response.text();
                new bootstrap.Modal(document.getElementById('show-project-task')).show();
            }

            async function showTaskById(id) {
                const response = await fetch('{{ route('project_tasks.show', ':id') }}'.replace(':id', id));
                if (!response.ok) return showToast('Có lỗi xảy ra!', 'bg-error');

                document.getElementById('actionTaskModal').innerHTML = await response.text();
                new bootstrap.Modal(document.getElementById('actionTaskModal')).show();
            }

            async function editTask(id, projectMember, taskMembers) {
                const response = await fetch('{{ route('project_tasks.edit', ':id') }}'.replace(':id', id));
                if (!response.ok) return showToast('Có lỗi xảy ra!', 'bg-error');

                document.getElementById('actionTaskModal').innerHTML = await response.text();
                new bootstrap.Modal(document.getElementById('actionTaskModal')).show();

                const slider = document.getElementById('editTaskProgress');
                const valueDisplay = document.getElementById('editProgressValue');
                let minProgress = parseInt(slider.dataset.minProgress) || 0;

                slider.addEventListener('input',
                    function() {
                        const value = parseInt(this
                            .value);

                        if (value <= minProgress) {
                            this.value =
                                minProgress;
                            return;
                        }

                        valueDisplay.textContent =
                            this.value + '%';
                    });

                initTagifyForInput(`input[name="task_members"]`, projectMember, false, taskMembers);
            }

            async function showProject(id) {
                const response = await fetch('{{ route('projects.show', ':id') }}'.replace(':id', id));
                if (!response.ok) return showToast('Có lỗi xảy ra!', 'bg-error');

                document.getElementById('show-edit-project').innerHTML = await response.text();
                new bootstrap.Modal(document.getElementById('show-edit-project')).show();
            }

            async function editProject(id, employee, memberOfProject) {
                const response = await fetch('{{ route('projects.edit', ':id') }}'.replace(':id', id));
                if (!response.ok) return showToast('Có lỗi xảy ra!', 'bg-error');

                document.getElementById('show-edit-project').innerHTML = await response.text();
                new bootstrap.Modal(document.getElementById('show-edit-project')).show();

                formatMoney();
                initTagifyForInput(`input[name="project_edit[project_members]"]`, employee, false,
                    memberOfProject);
            }

            function showToast(message, className) {
                const toastEl = document.getElementById('toast');
                toastEl.className = 'toast ' + className;
                document.getElementById('massagee').innerText = message;

                const toast = new bootstrap.Toast(toastEl, {
                    delay: 2000
                });
                toast.show();
            }
        });
    </script>
@endsection
