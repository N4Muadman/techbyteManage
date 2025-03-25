@extends('layout')

@section('content')<div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quản lý hành chính</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Quản lý lịch làm việc</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách lịch làm việc</h2>
                        @auth
                        @if (Auth::user()->hasPermissionOnPage('3', '6'))
                                <div class="d-flex">
                                    <button data-bs-toggle="modal" data-bs-target="#addWorkScheduleModal"
                                        class="btn btn-light-primary d-flex align-items-center gap-2 me-3"><i
                                            class="ti ti-plus"></i> Add
                                        new
                                        item</button>
                                    <button data-bs-toggle="modal" data-bs-target="#importModal"
                                        class="btn btn-danger d-flex align-items-center gap-2"><i
                                            class="fas fa-file-import"></i></button>
                                </div>
                            @endif
                        @endauth

                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('workschedule.index.admin') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 mb-2 col-sm-2">
                <select class="form-select" name="work_shift" id="">
                    <option value="" selected>Tìm kiếm theo ca</option>
                    @foreach ($work_shifts as $it)
                        @if ($it->id == request('work_shift'))
                            <option selected value="{{ $it->id }}">{{ $it->name }}</option>
                        @else
                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            @auth
                @if (Auth::user()->role_id != 3 && Auth::user()->role_id != 4)
                    <div class="col-6 mb-2 col-sm-2">
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
                @endif
            @endauth
            <div class="col-6  col-sm-2">
                <input type="date" class="form-control" placeholder="Tìm kiếm theo ngày" value="{{ request('date') }}"
                    name="date" id="">
            </div>
            <div class="col-6  col-sm-2">
                <button type="submit" class="btn btn-success  me-3">Tìm kiếm</button>
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
                                <tr class="text-center">
                                    <th>Id</th>
                                    <th>Ngày</th>
                                    <th>Ca làm việc</th>
                                    <th>Giờ bắt đầu</th>
                                    <th>Giờ kết thúc</th>
                                    @if (Auth::user()->hasPermissionOnPage('5', '6'))
                                        <th>Tổng nhân viên</th>
                                        <th>Chi tiết</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($work_schedule as $it)
                                    <tr>
                                        <td data-label="Id">{{ $loop->iteration }}</td>
                                        <td data-label="Ngày">{{ $it->date }}</td>
                                        <td data-label="Ca làm việc">{{ $it->work_shift->name }}</td>
                                        <td data-label="Giờ bắt đầu">{{ $it->work_shift->start_time }}</td>
                                        <td data-label="Giờ kết thúc">{{ $it->work_shift->end_time }}</td>
                                        @if (Auth::user()->hasPermissionOnPage('5', '6'))
                                            <td data-label="Tổng nhân viên">{{ $it->total_employee }}</td>
                                            <td data-label="Chi tiết" class=" text-center">
                                                <a href="#" style="margin-right: 10px" data-bs-toggle="modal"
                                                    data-bs-target="#detailWorkscheduleModal{{ $it->date . $it->work_shift_id }}"
                                                    class="employee-detail"><i class="fas fa-info-circle"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $work_schedule->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Thêm lịch làm việc bằng Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('workschedule.importfile.admin') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <label for="file">Chọn file Excel:</label>
                        <input type="file" name="file" class="form-control" required>
                        @if ($errors->has('file'))
                            <div class="text-danger mb-3">{{ $errors->first('password') }}</div>
                        @endif
                        <button type="submit" class="btn btn-danger mt-5">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- dialog chi tiet -->
    @foreach ($work_schedule as $it)
        <div class="modal fade" id="detailWorkscheduleModal{{ $it->date . $it->work_shift_id }}" tabindex="-1"
            aria-labelledby="detailWorkscheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="detailWorkscheduleModalLabel">Chi tiết lịch làm việc</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="d-flex flex-column">
                            <span><strong>Ngày :</strong> {{ $it->date }}</span>
                            <span><strong>Ca làm việc:</strong> {{ $it->work_shift->name }}</span>
                            <span><strong>Giờ bắt đầu:</strong> {{ $it->work_shift->start_time }}</span>
                            <span><strong>Giờ kết thúc:</strong> {{ $it->work_shift->end_time }}</span>
                        </p>
                        <p><strong>Nhân viên</strong></p>
                        <div class="row">
                            <div class="col-12">
                                <div class="card table-card">
                                    <div class="card-body pt-3">
                                        <div class="table-responsive">
                                            <table class="table table-hover text-center" id="pc-dt-simple">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Id</th>
                                                        <th>Họ tên</th>
                                                        <th>Chi nhánh</th>
                                                        <th>Chức vụ</th>
                                                        @if (Auth::user()->hasPermissionOnPage('4', '6'))
                                                        <th>Chỉnh sửa</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                    @php
                                                        $employee_ids = explode(', ', $it->employee_list);
                                                        $employees = App\Models\Employee::with('branch')
                                                            ->whereIn('id', $employee_ids)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($employees as $item)
                                                        <tr>
                                                            <td data-label="Id">{{ $item->id }}</td>
                                                            <td data-label="Họ tên">{{ $item->full_name }}</td>
                                                            <td data-label="Chi nhánh">{{ $item->branch->branch_name }}
                                                            </td>
                                                            <td data-label="Chức vụ">{{ $item->position }}</td>
                                                            <td>
                                                                @if (Auth::user()->hasPermissionOnPage('4', '6'))
                                                                <a class="edit-schedule avtar avtar-show avtar-xs btn-link-secondary" data-date="{{ $it->date }}"
                                                                    data-work_shift_id="{{ $it->work_shift_id }}"
                                                                    data-employee_id="{{ $item->id }}">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach



    <!-- dialog them lich lam viec -->
    <div class="modal fade" id="addWorkScheduleModal" tabindex="-1" aria-labelledby="addWorkScheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addWorkScheduleModalLabel">Thêm Lịch làm việc</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('workschedule.create.admin') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="form-label">Chọn Ngày Làm Việc:</label>
                            <input type="date" name="work_date" id="work_date" class="form-control" required>
                        </div>
                        <!-- Chọn ca làm việc -->
                        <div class="mb-3">
                            <label for="form-label">Chọn Ca Làm Việc:</label>
                            <select name="work_shift_id" id="work_shift" class="form-select" required>
                                @foreach ($work_shifts as $it)
                                    <option value="{{ $it->id }}">{{ $it->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if (Auth::user()->role_id == 1)
                            <!-- Chọn ca làm việc -->
                            <div class="mb-3">
                                <label for="form-label">Chọn chi nhánh:</label>
                                <select name="branch_id" id="branch" class="form-select" required>
                                    <option value="" selected>Chọn chi nhánh</option>
                                    @foreach ($branch as $it)
                                        <option value="{{ $it->id }}">{{ $it->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if (Auth::user()->role_id == 3)
                            <!-- Danh sách nhân viên -->
                            <div class="schedule-form-group">
                                <label>Chọn Nhân Viên:</label>
                                <div class="schedule-employee-list">
                                    @foreach ($employee as $item)
                                        <div class="schedule-employee-item">
                                            <input type="checkbox" name="employee_ids[]" value="{{ $item->id }}">
                                            <label>{{ $item->full_name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif (Auth::user()->role_id == 1)
                            <div class="schedule-form-group">
                                <label id="employee-lable">Vui lòng chọn chi nhánh để chọn nhân viên</label>
                                <div class="schedule-employee-list" id="employee-list"></div>
                            </div>
                        @endif
                        <!-- Nút lưu -->
                        <button type="submit" class="schedule-btn-submit">Lưu Lịch Làm Việc</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="edit-schedule"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchId = document.getElementById('branch');
            if(branchId){
                branchId.addEventListener('change', function() {
                    const branchId = this.value;
                    const employeeLable = document.getElementById('employee-lable');
                    const employeeList = document.getElementById('employee-list');

                    if (branchId) {
                        const url = `{{ route('workschedule.employeeOfBranch', ':branchId') }}`.replace(
                            ':branchId', branchId);
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data.status == 200) {
                                    employeeLable.textContent = 'Chọn nhân viên';
                                    list = '';
                                    data.employee.forEach(element => {
                                        list += `<div class="schedule-employee-item">
                                                    <input type="checkbox" name="employee_ids[]" value="${element.id}">
                                                    <label>${element.full_name}</label>
                                                </div>`;
                                    });
                                    employeeList.innerHTML = list;
                                } else {
                                    employeeLable.textContent = data.message;
                                    employeeList.innerHTML = ''
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            }

            document.querySelectorAll('.edit-schedule').forEach(function(element) {
                element.addEventListener('click', function() {
                    const date = this.dataset.date;
                    const work_shift_id = this.dataset.work_shift_id;
                    const employee_id = this.dataset.employee_id;
                    if (date, work_shift_id, employee_id) {
                        const url = `{{ route('workschedule.edit') }}`;
                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    employee_id: employee_id,
                                    work_shift_id: work_shift_id,
                                    work_date: date,
                                }),
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.text();
                            })
                            .then(html => {
                                document.getElementById('edit-schedule').innerHTML = html;
                                $('#editWork_scheduleModal').modal('show');
                            })
                            .catch((error) => {
                                console.error('Errore', error)
                            });
                    }
                });
            });
        });
    </script>
@endsection
