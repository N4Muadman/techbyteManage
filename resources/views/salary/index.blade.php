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
                        <li class="breadcrumb-item" aria-current="page">Quản lý tiền lương</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách tiền lương</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('salary.index.admin') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 col-sm-2 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo tên"
                    value="{{ request('full_name') }}" name="full_name" id="">
            </div>
            <div class="col-6 col-sm-2 mb-2">
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
            <div class="col-6 col-sm-2 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo chức vụ"
                    value="{{ request('position') }}" name="position" id="">
            </div>
            
            <div class="col-6 col-sm-2 mb-2">
                <input type="month" class="form-control" value="{{ request('start_month') }}"
                    name="start_month" id="">
            </div>
            <div class="col-6 col-sm-2 mb-2">
                <input type="month" class="form-control" value="{{ request('end_month') }}"
                    name="end_month" id="">
            </div>

            <div class="col-12 d-flex d-sm-block justify-content-between col-sm-2">
                <button type="submit" class="btn btn-success  me-3">Tìm kiếm</button>
                <a href="{{ route('salary.export.admin', request()->query()) }}"
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
                                    <th>ID</th>
                                    <th>Họ tên</th>
                                    <th>Chi nhánh</th>
                                    <th>Chức vụ</th>
                                    <th>Lương cơ bản</th>
                                    <th>Phụ cấp</th>
                                    <th>Thưởng</th>
                                    <th>Khoản trừ</th>
                                    <th>Thời gian tính lương</th>
                                    <th>Tổng lương</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salaries as $it)
                                    @php
                                        $total_salary = $it->base_salary + $it->allowance + $it->bonus - $it->deduction;
                                    @endphp
                                    <tr>
                                        <td>{{ $it->id }}</td>
                                        <td>{{ $it->employee?->full_name }}</td>
                                        <td>{{ $it->employee?->branch?->branch_name }}</td>
                                        <td>{{ $it->employee?->position }}</td>
                                        <td>{{ number_format($it->base_salary) . ' đ' }}</td>
                                        <td>{{ number_format($it->allowance) . ' đ' }}</td>
                                        <td>{{ number_format($it->bonus) . ' đ' }}</td>
                                        <td>{{ number_format($it->deduction) . ' đ' }}</td>
                                        <td>{{ $it->salary_date }}</td>
                                        <td>{{ number_format($total_salary) . ' đ' }}</td>
                                        <td>
                                            @if (Auth::user()->hasPermissionOnPage('3', '2'))
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#info-salary-{{ $it->id }}"
                                                    class="employee-detail avtar avtar-xs btn-link-secondary"><i
                                                        class="ti ti-info-circle f-18"></i></a>
                                            @endif

                                            @if (Auth::user()->hasPermissionOnPage('2', '2'))
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-salary-{{ $it->id }}"
                                                    class="employee-edit avtar avtar-xs btn-link-secondary"><i
                                                        class="ti ti-edit f-18"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $salaries->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        function formatHour($totalHours)
        {
            $hours = floor($totalHours);
            // Tính phần phút
            $minutes = ($totalHours - $hours) * 60;
            $minutes = round($minutes);
            return $hours . ' giờ ' . $minutes . ' phút';
        }
    @endphp
    @foreach ($salaries as $it)
        <div class="modal fade" id="edit-salary-{{ $it->id }}" tabindex="-1"
            aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="editEmployeeModalLabel">Cập nhật lương nhân viên</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('salary.update.admin', $it->id) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-lable">Phụ cấp</label>
                                <input type="text" class="form-control money" name="allowance"
                                    placeholder="Nhập số tiền phụ cấp" value="{{ $it->allowance }}">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-lable">Thưởng</label>
                                <input type="text" class="form-control money" name="bonus"
                                    placeholder="Nhập số tiền thưởng" value="{{ $it->bonus }}">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-lable">Khoản trừ</label>
                                <input type="text" class="form-control money" name="deduction"
                                    placeholder="Nhập số tiền khoản trừ" value="{{ $it->deduction }}">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-lable">Mô tả</label>
                                <textarea name="description" class="form-control" cols="30" rows="3" placeholder="Nhập mô tả">{{ $it->description }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Chỉnh sửa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="info-salary-{{ $it->id }}" tabindex="-1"
            aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="editEmployeeModalLabel">Chi tiết về lương và giờ làm việc</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Họ tên:</p>
                                                <span class="fw-bold">{{ $it->employee?->full_name }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Chi nhánh:</p>
                                                <span class="fw-bold">{{ $it->employee?->branch?->branch_name }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Chức vụ:</p>
                                                <span class="fw-bold">{{ $it->employee?->position }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Lương cơ bản:</p>
                                                <span
                                                    class="fw-bold">{{ number_format($it->base_salary) . ' đ' }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Phụ cấp:</p>
                                                <span
                                                    class="fw-bold">{{ number_format($it->allowance) . ' đ' }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Thưởng:</p>
                                                <span
                                                    class="fw-bold">{{ number_format($it->bonus) . ' đ' }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Khoản trừ:</p>
                                                <span
                                                    class="fw-bold">{{ number_format($it->deduction) . ' đ' }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Thời gian tính lương:</p>
                                                <span class="fw-bold">{{ $it->salary_date }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Tổng tiền lương:</p>
                                                <span
                                                    class="fw-bold">{{ number_format($total_salary) . ' đ' }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Tổng giờ làm:</p>
                                                <span class="fw-bold">{{ formatHour($it->total_work) }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Tổng số lần đi muốn:</p>
                                                <span class="fw-bold text-danger">{{ $it->total_late_arrivals ?? 0 }}
                                                    lần</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-worker-profile">Tổng số giờ đi muộn:</p>
                                                <span
                                                    class="fw-bold text-danger">{{ formatHour($it->total_late_hours) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteEmployeeModal-{{ $it->id }}" tabindex="-1"
            aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteEmployeeModalLabel">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có muốn xóa lương của nhân viên <strong>{{ $it->employee?->full_name }}</strong> không?</p>
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
    @endforeach
@endsection
