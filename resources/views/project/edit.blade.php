 <div class="modal-dialog modal-xl modal-dialog-centered">
     <div class="modal-content">
         <div class="modal-header">
             <h4 class="modal-title d-flex align-items-center" id="addProjectModalLabel">
                 <i class="bi bi-rocket-takeoff me-3 fs-2"></i>
                 Chỉnh sửa dự án
             </h4>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>

         <div class="modal-body">
             <form id="addProjectForm" action="{{ route('projects.update', $project->id) }}" method="post">
                 @csrf
                 @method('put')
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
                                     class="form-control"
                                     placeholder="Nhập tên dự án" name="project_edit[name]" value="{{ $project->name }}"
                                     required>
                                 <i class="ti ti-pencil input-icon"></i>
                             </div>
                         </div>


                         <div class="col-md-6">
                             <label class="form-label">
                                 <i class="ti ti-tag me-1"></i>
                                 Loại dự án <span class="required-mark">*</span>
                             </label>
                             <select class="form-select"
                                 name="project_edit[type]" required>
                                 <option value="">🎯 Chọn loại dự án</option>
                                 <option value="internal" {{ $project->type == 'internal' ? 'selected' : '' }}>🏢
                                     Nội bộ</option>
                                 <option value="customer" {{ $project->type == 'customer' ? 'selected' : '' }}>👥
                                     Khách hàng
                                 </option>
                             </select>
                         </div>

                         <div class="col-md-6">
                             <label class="form-label">
                                 <i class="ti ti-calendar-event me-1"></i>
                                 Ngày bắt đầu <span class="required-mark">*</span>
                             </label>
                             <input type="date"
                                 class="form-control"
                                 name="project_edit[start_date]" value="{{ $project->start_date }}" required>

                         </div>

                         <div class="col-md-6">
                             <label class="form-label">
                                 <i class="ti ti-calendar-check me-1"></i>
                                 Ngày kết thúc <span class="required-mark">*</span>
                             </label>
                             <input type="date"
                                 class="form-control"
                                 name="project_edit[end_date]" value="{{ $project->end_date }}" required>
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
                                     class="form-control money"
                                     name="project_edit[total_cost]" value="{{ $project->total_cost }}"
                                     placeholder="0 VNĐ">
                             </div>
                         </div>

                         <div class="col-md-6">
                             <label class="form-label">
                                 <i class="ti ti-person-badge me-1"></i>
                                 Quản lý dự án <span class="required-mark">*</span>
                             </label>
                             <select class="form-select"
                                 name="project_edit[leader_id]" required>
                                 <option value="">👤 Chọn quản lý dự án</option>
                                 @foreach ($userEmployees as $user)
                                     <option value="{{ $user->id }}"
                                         {{ $project->leader_id == $user->id ? 'selected' : '' }}>👨‍💼
                                         {{ $user->employee?->full_name }}
                                     </option>
                                 @endforeach
                             </select>
                         </div>

                         <div class="col-12">
                             <label class="form-label">
                                 <i class="ti ti-people me-1"></i>
                                 Thành viên dự án <span class="required-mark">*</span>
                             </label>
                             <div class="input-group">
                                 <input name="project_edit[project_members]"
                                     class="form-control"
                                     placeholder="Chọn thành viên dự án">

                             </div>
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
                                     class="form-control"
                                     name="project_edit[archive_link]" value="{{ $project->archive_link }}">

                             </div>
                         </div>

                         <div class="col-md-6">
                             <label class="form-label">
                                 <i class="ti ti-file-earmark-text me-1"></i>
                                 Link tài liệu
                             </label>
                             <div class="input-group">
                                 <input type="url" placeholder="https://docs.google.com/..."
                                     class="form-control"
                                     name="project_edit[document_link]" value="{{ $project->document_link }}">
                             </div>
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
                             <textarea name="project_edit[description]" placeholder="Mô tả chi tiết về dự án, mục tiêu, yêu cầu và ghi chú khác..."
                                 class="form-control" rows="3">{{ $project->description }}</textarea>
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
