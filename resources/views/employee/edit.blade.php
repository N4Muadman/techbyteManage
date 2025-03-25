<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editEmployeeModalLabel">Chỉnh sửa nhân viên</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.update.admin', $employee->id) }}" method="post"
                    >
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="hoten" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" name="username" value="{{ $employee->user->username }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="hoten" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" name="password" >
                            </div>
                            <div class="mb-3">
                                <label for="hoten" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="full_name" value="{{ $employee->full_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="ngaysinh" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" name="dateBirth" value="{{ $employee->date_of_birth }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="gioitinh" class="form-label">Giới tính</label>
                                <select class="form-select" name="gender" required>
                                    @if($employee->gender == 'Nam')
                                        <option selected value="Nam">Nam</option>
                                        <option value="Nữ">Nữ</option>
                                    @else
                                        <option value="Nam">Nam</option>
                                        <option selected value="Nữ">Nữ</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="sdt" value="{{ $employee->phone_number }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" value="{{ $employee->address }}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $employee->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="chucvu" class="form-label">Chức vụ</label>
                                <input type="text" class="form-control" name="position" value="{{ $employee->position }}" >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lương cơ bản</label>
                                <input type="text" value="{{ $employee->base_salary }}" class="form-control money" name="base_salary" required>
                            </div>
                            <div class="mb-3">
                                <label for="tenkhoa" class="form-label">Chi nhánh</label>
                                <select class="form-select" name="branch" required aria-label="Default select example">
                                    <option value="">Chọn chi nhánh</option>
                                    @foreach ($branch as $item)
                                        @if($item->id == $employee->branch_id)
                                            <option selected value="{{ $item->id }}">{{ $item->branch_name }}</option>
                                        @else
                                            <option value="{{ $item->id }}">{{ $item->branch_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="chucvu" class="form-label">Thông tin thêm</label>
                                <textarea name="profile" class="form-control" cols="10" rows="3">{{ $employee->profile }}</textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Chỉnh sửa</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function (){
        $('#editEmployeeModal').modal('show');
    })
</script>
