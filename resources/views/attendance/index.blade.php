@extends('layout')
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quản lý hành chính</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Quản lý chấm công</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách chấm công</h2>

                        @if (Auth::user()->hasPermissionOnPage('1', '13'))
                            <button data-bs-toggle="modal" data-bs-target="#addAttenModal"
                                class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i> Add
                                new
                                item</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('attendance.index.admin') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 mb-2 col-sm-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo tên"
                    value="{{ request('full_name') }}" name="full_name" id="">
            </div>
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
            <div class="col-6 mb-2 col-sm-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo chức vụ"
                    value="{{ request('position') }}" name="position" id="">
            </div>
            <div class="col-6 mb-2 col-sm-2">
                <input type="date" class="form-control" placeholder="Tìm kiếm theo ngày" value="{{ request('date') }}"
                    name="date" id="">
            </div>
            <div class="col-12 d-flex justify-content-between  d-sm-block col-sm-3">
                <button type="submit" class="btn btn-success  me-3">Tìm kiếm</button>
                <a href="{{ route('attendance.export.admin', ['full_name' => request('full_name'), 'branch' => request('branch'), 'position' => request('position'), 'date' => request('date')]) }}"
                    class="btn btn-danger">
                    Báo cáo <i class="ms-2 fas fa-file-export"></i>
                </a>
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
                                    <th>Id</th>
                                    <th>Họ tên</th>
                                    <th>Chức vụ</th>
                                    <th>Chi nhánh</th>
                                    <th>Ngày</th>
                                    <th>Ca làm</th>
                                    <th>Giờ vào</th>
                                    <th>Tổng số giờ</th>
                                    <th>Trạng thái</th>
                                    @auth
                                        @if (Auth::user()->hasPermissionOnPage('4', '13'))
                                            <th>Chức năng</th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    function formatHour($totalHours)
                                    {
                                        $hours = floor($totalHours);
                                        // Tính phần phút
                                        $minutes = ($totalHours - $hours) * 60;
                                        $minutes = round($minutes);
                                        return $hours . ' giờ ' . $minutes . ' phút';
                                    }
                                    function calculatehour($attendanceTime, $scheduleTime, $checkIn = false)
                                    {
                                        $attendanceTime = \Carbon\Carbon::parse($attendanceTime);
                                        $scheduleTime = \Carbon\Carbon::parse($scheduleTime);
                                        $diffInMinutes = abs($attendanceTime->diffInMinutes($scheduleTime)) / 60;

                                        // Xác định xem nhân viên check-in sớm hay muộn
                                        if ($checkIn) {
                                            if ($attendanceTime->greaterThan($scheduleTime)) {
                                                return "<span class='text-danger'>Muộn " .
                                                    formatHour($diffInMinutes) .
                                                    '</span>';
                                            } else {
                                                return "<span class='text-success'>Sớm " .
                                                    formatHour($diffInMinutes) .
                                                    '</span>';
                                            }
                                        } else {
                                            if ($attendanceTime->greaterThan($scheduleTime)) {
                                                return "<span class='text-danger'> CheckIn muộn </span>";
                                            } else {
                                                return "<span class='text-success'> CheckIn Sớm </span>";
                                            }
                                        }
                                    }

                                @endphp
                                @foreach ($attendance as $it)
                                    <tr>
                                        <td data-label="Id">{{ $it->id }}</td>
                                        <td data-label="Họ tên">{{ $it->employee->full_name }}</td>
                                        <td data-label="Chức vụ">{{ $it->employee->position }}</td>
                                        <td data-label="Chi nhánh">{{ $it->employee->branch->branch_name }}</td>
                                        <td data-label="Ngày">{{ $it->date }}</td>
                                        <td>{{ $it->work_schedule->name }}</td>
                                        <td data-label="Giờ vào">{{ $it->check_in }}<br> {!! calculatehour($it->check_in, $it->work_schedule->start_time, true) !!}</span>
                                        </td>
                                        <td data-label="Tổng số giờ">{{ formatHour($it->working_hours) }}</td>
                                        <td class="text-success">{!! calculatehour($it->check_in, $it->work_schedule->start_time, false) !!}</td>
                                        <td data-label="Phê duyệt" class=" text-center">
                                            @if (Auth::user()->hasPermissionOnPage('4', '13'))
                                                <a href="#" data-bs-toggle="modal"
                                                    class="avtar avtar-show avtar-xs btn-link-secondary"
                                                    data-bs-target="#deleteAttenModal-{{ $it->id }}"><i
                                                        class="fa fa-trash"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $attendance->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
        {{-- @foreach ($attendance->where('status', 0) as $it)
            <div class="modal fade" id="approveAttendance-{{ $it->id }}" tabindex="-1"
                aria-labelledby="approveAttendanceLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approveAttendanceLabel">Xác nhận phê duyệt</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có muốn phê duyệt chấm công của nhân viên
                                <strong>{{ $it->employee->full_name }}</strong> không?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <form action="{{ route('attendance.approve.admin', $it->id) }}" method="post"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">OK</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach --}}
        @foreach ($attendance as $it)
            <!-- Modal Xóa -->
            <div class="modal fade" id="deleteAttenModal-{{ $it->id }}" tabindex="-1"
                aria-labelledby="deleteAttenModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteAttenModalLabel">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có muốn xóa chấm công của <strong>{{ $it->employee->full_name }}</strong> không?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <form action="{{ route('attendance.delete.admin', $it->id) }}" method="post"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="modal fade" id="editAttenModal-{{ $it->id }}" tabindex="-1"
                aria-labelledby="editAttenModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="editAttenModalLabel">Sửa chấm công</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editEmployeeForm" action="{{ route('attendance.update.admin', $it) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-lable"><strong>Nhân viên:
                                            {{ $it->employee->full_name }}</strong></label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-lable">Chọn ca làm</label>
                                    <select name="work_schedule_id" class="form-select" required>
                                        @foreach ($work_schedules->where('employee_id', $it->employee_id) as $wk)
                                            @if ($wk->id == $it->work_schedule_id)
                                                <option value="{{ $wk->id }}" selected>{{ $wk->work_shift->name }}
                                                    : {{ $wk->date }}</option>
                                            @else
                                                <option value="{{ $wk->id }}">{{ $wk->work_shift->name }} :
                                                    {{ $wk->date }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-lable">Chọn ngày</label>
                                    <input type="date" class="form-control" value="{{ $it->date }}" name="date"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-lable">Giờ checkin</label>
                                    <input type="time" class="form-control" value="{{ $it->check_in }}" name="check_in"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-lable">Giờ checkout</label>
                                    <input type="time" class="form-control" value="{{ $it->check_out }}"
                                        name="check_out" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Chỉnh sửa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div> --}}
        @endforeach
        <!-- Modal Thêm mới -->
        <div class="modal fade" id="addAttenModal" tabindex="-1" aria-labelledby="addAttenModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addAttenModalLabel">Thêm mới chấm công</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEmployeeForm" action="{{ route('attendance.create.admin') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-lable">Chọn nhân viên</label>
                                <select name="employee_id" class="form-select" required id="employee">
                                    <option value="" selected>Chọn nhân viên</option>
                                    @foreach ($employees as $e)
                                        <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-lable">Chọn ca làm</label>
                                <select name="work_schedule_id" class="form-select" required id="work_schedule">
                                    <option value="" selected>Chọn ca làm</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-lable">Chọn ngày</label>
                                <input type="date" class="form-control" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-lable">Giờ checkin</label>
                                <input type="time" class="form-control" name="check_in" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-lable">Giờ checkout</label>
                                <input type="time" class="form-control" name="check_out" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <script>
        document.getElementById('employee').addEventListener('change', function() {
            const employeeId = this.value;
            const work_schedule = document.getElementById('work_schedule');
            if (employeeId) {
                const url = `{{ route('workschedule.getWorkSche', ':employeeId') }}`.replace(':employeeId',
                    employeeId);
                fetch(url)
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Lỗi mạng hoặc không lấy được dữ liệu');
                    })
                    .then(data => {
                        let listWork_schedule = '';
                        data.workSchedules.forEach(element => {
                            listWork_schedule +=
                                `<option value="${element.id}">${element.work_shift.name} : ${element.date}</option>`;
                        });
                        work_schedule.innerHTML = listWork_schedule;
                    })
                    .catch(error => console.error("Error: ", error));
            }
        })
    </script>
@endsection
