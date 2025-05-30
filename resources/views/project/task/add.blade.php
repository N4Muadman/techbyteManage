<div class="card task-item mb-3" data-index="0">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Công việc #<span class="task-number">1</span></h6>
            <button type="button" class="btn btn-sm btn-danger btn-remove-task">🗑
                Xóa</button>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Tiêu đề công việc <span class="text-danger">(*)</span></label>
                <input type="text" class="form-control @error('project_tasks.*.title') border-danger @enderror"
                    name="project_tasks[0][title]" placeholder="Nhập tiêu đề" required>
                @error('project_tasks.*.title')
                    <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">Thành viên dự án <span class="text-danger">(*)</span></label>
                <input type="text"
                    class="form-control  @error('project_tasks.*.task_members') border-danger @enderror"
                    name="project_tasks[0][task_members]" placeholder="Chọn thành viên" required>
                @error('project_tasks.*.task_members')
                    <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Hạn hoàn thành <span class="text-danger">(*)</span></label>
                <input type="date" class="form-control @error('project_tasks.*.due_date') border-danger @enderror"
                    name="project_tasks[0][due_date]" required>
                @error('project_tasks.*.due_date')
                    <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Mô tả công việc</label>
                <textarea class="form-control @error('project_tasks.*.description') border-danger @enderror"
                    name="project_tasks[0][description]" rows="2" required></textarea>
                @error('project_tasks.*.description')
                    <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
