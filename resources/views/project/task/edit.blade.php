 <div class="modal-dialog modal-lg">
     <div class="modal-content">
         <form action="{{ route('project_tasks.update', $task->id) }}" method="post">
             @method('put')
             @csrf
             <div class="modal-header">
                 <h5 class="modal-title" id="editTaskModalLabel">
                     <i class="bi bi-pencil text-warning me-2"></i>
                     Sửa công việc
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="row show-task {{ $task->status_class }}">
                     <div class="col-md-8">
                         <div class="mb-3">
                             <label for="editTaskTitle" class="form-label">Tiêu đề công việc *</label>
                             <input type="text" class="form-control" id="editTaskTitle" value="{{ $task->title }}"
                                 name="title" required>
                         </div>
                         <div class="mb-3">
                             <label for="editTaskDescription" class="form-label">Mô tả chi tiết</label>
                             <textarea class="form-control" id="editTaskDescription" name="description" rows="5" required>{{ $task->description }}</textarea>
                         </div>
                         <div class="mb-3">

                             <label for="editTaskProgress" class="form-label">Tiến độ (%)</label>
                             <div class="progress-slider-container">
                                 <input type="range" class="form-range" id="editTaskProgress" min="0"
                                     max="100" value="{{ $task->progress }}"
                                     data-min-progress="{{ $task->progress }}" name="progress">
                             </div>
                             <div class="d-flex justify-content-between small task-progress-text mt-2">
                                 <span>0%</span>
                                 <span id="editProgressValue">{{ $task->progress }}%</span>
                                 <span>100%</span>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="mb-3">
                             <label for="editTaskStatus" class="form-label">Trạng thái</label>
                             <div>
                                 <span id="viewTaskStatus" class="badge task-status">{{ $task->status_label }}</span>
                             </div>
                         </div>
                         <div class="mb-3">
                             <label for="editTaskDueDate" class="form-label">Ngày hết hạn</label>
                             <input type="date" class="form-control" id="editTaskDueDate" name="due_date"
                                 value="{{ $task->due_date }}" required>
                         </div>
                         <div class="mb-3">
                             <label for="editTaskMembers" class="form-label">Thành viên</label>
                             <input type="text" class="form-control  @error('task_members') border-danger @enderror"
                                 name="task_members" placeholder="Chọn thành viên">
                             @error('task_members')
                                 <p class="text-danger mt-2">{{ $message }}</p>
                             @enderror
                         </div>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>

                 <button type="submit" class="btn btn-warning">
                     <i class="ti ti-check-lg me-1"></i>
                     Cập nhật công việc
                 </button>
             </div>
         </form>
     </div>
 </div>
