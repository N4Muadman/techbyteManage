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
                        <li class="breadcrumb-item" aria-current="page">Quản lý nghỉ phép</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách nghỉ phép</h2>
                        @auth
                            @if (Auth::user()->hasPermissionOnPage('1', '5'))
                                <button data-bs-toggle="modal" data-bs-target="#addLeaveModal"
                                    class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i> Add
                                    new
                                    item</button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('leave.index.admin') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 col-sm-2 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo tên"
                    value="{{ request('full_name') }}" name="full_name" id="">
            </div>
            @auth
                @if (Auth::user()->role_id != 3)
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
                @endif
            @endauth
            <div class="col-6 col-sm-2 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo chức vụ"
                    value="{{ request('position') }}" name="position" id="">
            </div>
            <div class="col-6 col-sm-2 mb-2">
                <select class="form-select" name="leave_type" id="">
                    <option value="" selected>Tìm kiếm theo loại</option>
                    <option value="Nghỉ phép">Nghỉ phép</option>
                    <option value="Nghỉ ốm">Nghỉ ốm</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div class="col-6 col-sm-2 mb-2">
                <input type="date" class="form-control" placeholder="Tìm kiếm theo ngày" value="{{ request('date') }}"
                    name="date" id="">
            </div>
            <div class="col-6 col-sm-2">
                <button type="submit" class="btn btn-success">Tìm kiếm</button>
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
                                    <th>Bắt đầu nghỉ</th>
                                    <th>Kết thúc nghỉ</th>
                                    <th>Lý do chi tiết</th>
                                    <th>Trạng thái</th>
                                    @auth
                                        @if (Auth::user()->role_id != 4)
                                            <th>Phê duyệt</th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leave as $it)
                                    <tr>
                                        <td data-label="Id">{{ $it->id }}</td>
                                        <td data-label="Họ tên">{{ $it->employee->full_name }}</td>
                                        <td data-label="Chức vụ">{{ $it->employee->position }}</td>
                                        <td data-label="Bắt đầu nghỉ">{{ $it->start_date }}</td>
                                        <td data-label="Kết thúc nghỉ">{{ $it->end_date }}</td>
                                        <td data-label="Lý do chi tiết">{{ $it->reason_description }}</td>
                                        @if ($it->leave_status == 0)
                                            <td data-label="Trạng thái" class="text-danger">Chưa phê duyệt</td>
                                            @if (Auth::user()->hasPermissionOnPage('6', '5'))
                                                <td data-label="Phê duyệt" class=" text-center">
                                                    <a href="#" title="Phê duyệt" data-bs-toggle="modal"
                                                        data-bs-target="#approveLeave-{{ $it->id }}">
                                                        <i class="fas fa-toggle-off me-3" style="font-size: 28px"></i>
                                                    </a>
                                                    <a href="#" title="Từ chối" data-bs-toggle="modal"
                                                        data-bs-target="#refuseLeave-{{ $it->id }}">
                                                        <i style="font-size: 28px; color:#fb0101" class="fas fa-toggle-off "
                                                            style="font-size: 28px"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        @elseif ($it->leave_status == 1)
                                            <td data-label="Trạng thái" class="text-success">Đã phê duyệt</td>
                                            @if (Auth::user()->hasPermissionOnPage('6', '5'))
                                                <td data-label="Phê duyệt" class=" text-center">
                                                    <i class="fas fa-toggle-on" style="font-size: 28px; color:#009879"></i>
                                                    @if (Auth::user()->hasPermissionOnPage('4', '5'))
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#deleteLeaveModal-{{ $it->id }}">
                                                            <i class="ti ti-trash f-20 ms-3" style="font-size: 20px;"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                        @elseif ($it->leave_status == 2)
                                            <td data-label="Trạng thái" class="text-danger">Đã bị từ chối</td>
                                            @if (Auth::user()->hasPermissionOnPage('6', '5'))
                                                <td data-label="Phê duyệt" class=" text-center">
                                                    <i class="fas fa-toggle-on"
                                                        style="font-size: 28px; color:#fb0101"></i>
                                                    @if (Auth::user()->hasPermissionOnPage('4', '5'))
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#deleteLeaveModal-{{ $it->id }}">
                                                            <i class="ti ti-trash f-20 ms-3" style="font-size: 20px;"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $leave->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($leave as $it)
        @if ($it->leave_status == 0)
            <!-- Modal duyệt -->
            <div class="modal fade" id="approveLeave-{{ $it->id }}" tabindex="-1"
                aria-labelledby="approveLeaveLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approveLeaveLabel">Xác nhận phê duyệt</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có muốn phê duyệt nghỉ phép cho nhân viên
                                <strong>{{ $it->employee->full_name }}</strong> không?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <form action="{{ route('leave.approve.admin', $it->id) }}" method="post"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Phê duyệt</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal từ chối -->
            <div class="modal fade" id="refuseLeave-{{ $it->id }}" tabindex="-1"
                aria-labelledby="refuseLeaveLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="refuseLeaveLabel">Xác nhận từ chối</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có muốn từ chối nghỉ phép của nhân viên <strong>{{ $it->employee->full_name }}</strong>
                                không?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <form action="{{ route('leave.refuse.admin', $it->id) }}" method="post"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Từ chối</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Modal delete -->
            <div class="modal fade" id="deleteLeaveModal-{{ $it->id }}" tabindex="-1"
                aria-labelledby="deleteLeaveLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteLeaveLabel">Xác nhận từ chối</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có muốn từ chối nghỉ phép của nhân viên <strong>{{ $it->employee->full_name }}</strong>
                                không?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <form action="{{ route('leave.delete.admin', $it->id) }}" method="post"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Từ chối</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Modal add -->
    <div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addLeaveModalModalLabel">Đăng kí nghỉ phép</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addLeaveModalForm" action="{{ route('leave.create.admin') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Ngày bắt đầu nghỉ</label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày kết thúc nghỉ</label>
                                    <input type="date" class="form-control" name="end_date" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Lý do chi tiết</label>
                                    <textarea name="reason_description" class="form-control" cols="10" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Đăng kí</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
