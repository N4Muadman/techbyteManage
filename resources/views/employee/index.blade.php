@extends('layout')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quản lý nhân sự</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Quản lý nhân viên</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách nhân viên</h2>
                        @if (Auth::user()->hasPermissionOnPage('3', '1'))
                            <button data-bs-toggle="modal" data-bs-target="#addEmployeeModal"
                            class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i> Add new
                            item</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('employee.index.admin') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 col-sm-3 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo tên"
                    value="{{ request('full_name') }}" name="full_name" id="">
            </div>
            <div class="col-6 col-sm-3 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo chức vụ"
                    value="{{ request('position') }}" name="position" id="">
            </div>
            <div class="col-12 col-sm-3 mb-2">
                <select class="form-select" name="branch" id="">
                    <option value="" selected>Tìm kiếm theo chi nhánh</option>
                    @foreach ($branch as $it)
                        @if ($it->id == request('branch'))
                            <option selected value="{{ $it->id }}">{{ $it->branch_name }}</option>
                        @else
                            <option value="{{ $it->id }}">{{ $it->branch_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex d-sm-block col-sm-3 justify-content-between">
                <button type="submit" class="btn btn-success  me-3">Tìm kiếm</button>
                @if (request('status'))
                    <a class="btn btn-primary" href="{{ route('employee.index.admin') }}">Nhân viên đang làm</a>
                @else
                    <a class="btn btn-primary "
                        href="{{ route('employee.index.admin') . '?status=nhan-vien-nghi-viec' }}">Nhân viên nghỉ việc</a>
                @endif
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-hover text-center" id="pc-dt-simple">

                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Ngày sinh</th>
                                    <th>Giới tính</th>
                                    <th>Địa chỉ</th>
                                    <th>SDT</th>
                                    <th>Chức vụ</th>
                                    <th>Chi nhánh</th>
                                    <th>Ngày bắt đầu làm</th>
                                    @if (request('status'))
                                        <th>Ngày nghỉ việc</th>
                                        <th>Trạng thái</th>
                                    @else
                                        <th>Lương cơ bản</th>
                                        <th>Chức năng</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employee as $it)
                                    <tr>
                                        <td data-label="Họ tên">{{ $it->full_name }}</td>
                                        <td data-label="Ngày sinh">{{ $it->date_of_birth }}</td>
                                        <td data-label="Giới tính">{{ $it->gender }}</td>
                                        <td data-label="Địa chỉ">{{ $it->address }}</td>
                                        <td data-label="SDT">{{ $it->phone_number }}</td>
                                        <td data-label="Chức vụ">{{ $it->position }}</td>
                                        <td data-label="Chi nhánh">{{ $it->branch->branch_name }}</td>
                                        <td data-label="Ngày bắt đầu làm">{{ $it->start_date }}</td>
                                        @if (request('status'))
                                            <td data-label="Ngày nghỉ việc">{{ $it->end_date }}</td>
                                            <td data-label="Trạng thái" class="text-danger">Đã nghỉ việc</td>
                                        @else
                                            <td data-label="Lương cơ bản">{{ number_format($it->base_salary) . ' đ' }}</td>
                                            <td class="d-flex" style="margin-bottom: -5px">
                                                @if (Auth::user()->hasPermissionOnPage('5', '1'))
                                                <a href="#" data-id="{{ $it->id }}"
                                                    class="employee-detail avtar avtar-xs btn-link-secondary"><i class="fas fa-info-circle"></i></a>
                                                @endif

                                                @if (Auth::user()->hasPermissionOnPage('4', '1'))
                                                <a href="#" data-id="{{ $it->id }}"
                                                    class="employee-edit avtar avtar-xs btn-link-secondary"><i class="fas fa-user-edit"></i></a>
                                                @endif
                                                @if (Auth::user()->hasPermissionOnPage('7', '1'))
                                                    <a href="#" data-bs-toggle="modal" class="avtar avtar-xs btn-link-secondary"
                                                        data-bs-target="#empowerEmployeeModal-{{ $it->id }}"><i
                                                            class="fas fa-user-cog"></i></a>
                                                @endif

                                                @if (Auth::user()->hasPermissionOnPage('6', '1'))
                                                <a href="#" data-bs-toggle="modal" class="avtar avtar-xs btn-link-secondary"
                                                    data-bs-target="#deleteEmployeeModal-{{ $it->id }}"><i
                                                        class="ti ti-trash f-18"></i></a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $employee->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($employee as $it)
        <!-- Modal Xóa -->
        <div class="modal fade" id="deleteEmployeeModal-{{ $it->id }}" tabindex="-1"
            aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteEmployeeModalLabel">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có muốn xóa nhân viên <strong>{{ $it->full_name }}</strong> không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form action="{{ route('employee.delete.admin', $it->id) }}" method="post"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">OK</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if (Auth::user()->role_id == 1)
            <!-- Modal phân quyền -->
            <div class="modal fade" id="empowerEmployeeModal-{{ $it->id }}" tabindex="-1"
                aria-labelledby="empowerEmployeeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="empowerEmployeeModalLabel">Phân quyền nhân viên</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="empowerEmployeeForm" action="{{ route('employee.empower.admin', $it->id) }}"
                                method="post">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label class="form-label">Họ tên:
                                                <strong>{{ $it->full_name }}</strong></label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Vai trò hiện tại:
                                                <strong>{{ $it?->user?->role->name }}</strong></label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Vai trò</label>
                                            <select class="form-select" name="role_id"
                                                aria-label="Default select example">
                                                <option selected>Chọn vai trò</option>
                                                @foreach ($role as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Phân quyền</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    <!-- Modal Thêm mới -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addEmployeeModalLabel">Thêm mới nhân viên</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm" action="{{ route('employee.create.admin') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="hoten" class="form-label">Tên đăng nhập</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="hoten" class="form-label">Mật khẩu</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="hoten" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" name="full_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ngaysinh" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" name="dateBirth" required>
                                </div>
                                <div class="mb-3">
                                    <label for="gioitinh" class="form-label">Giới tính</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="Nam">Nam</option>
                                        <option value="Nữ">Nữ</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="sdt" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" name="sdt" required>
                                </div>
                                <div class="mb-3">
                                    <label for="diachi" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" name="address" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="diachi" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="chucvu" class="form-label">Ngày bắt đầu làm</label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="chucvu" class="form-label">Chức vụ</label>
                                    <input type="text" class="form-control" name="position" required>
                                </div>
                                <div class="mb-3">
                                    <label for="sdt" class="form-label">Lương cơ bản</label>
                                    <input type="text" class="form-control money" name="base_salary" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Chi nhánh</label>
                                    <select class="form-select" name="branch" aria-label="Default select example">
                                        <option selected>Chọn chi nhánh</option>
                                        @foreach ($branch as $item)
                                            <option value="{{ $item->id }}">{{ $item->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="chucvu" class="form-label">Thông tin thêm</label>
                                    <textarea name="profile" class="form-control" cols="10" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="employDetailDialog"></div>
    <div id="employEditDialog"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.money').inputmask('currency', {
                prefix: '',
                suffix: ',000 đ/ giờ',
                autoUnmask: true,
                digits: 0,
                digitsOptional: false,
                placeholder: '0'
            });
            $('.employee-detail').click(function() {
                const employee_id = $(this).data('id');
                if (employee_id) {
                    console.log(employee_id);

                    $.ajax({
                        url: "{{ url('admin/quan-ly-nhan-vien/chi-tiet-nhan-vien') }}/" +
                            employee_id,
                        type: 'GET',
                        success: function(response) {
                            $('#employDetailDialog').html(response);
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            // Xử lý lỗi nếu có
                            console.error('Error occurred:', xhr.responseText);
                        }
                    });
                }
            });
            $('.employee-edit').click(function() {
                const employee_id = $(this).data('id');
                if (employee_id) {
                    console.log(employee_id);

                    $.ajax({
                        url: "{{ url('admin/quan-ly-nhan-vien/chinh-sua-nhan-vien') }}/" +
                            employee_id,
                        type: 'GET',
                        success: function(response) {
                            $('#employDetailDialog').html(response);
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            // Xử lý lỗi nếu có
                            console.error('Error occurred:', xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
    @if (session()->has('success'))
        <script>
            toastr.success({{ session('success') }}, 'Thành công', {
                timeOut: 12000
            });
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            toastr.error({{ session('error') }}, 'Thất bại', {
                timeOut: 12000
            });
        </script>
    @endif
@endsection
