@extends('layout')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Điều động nhân sự</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách điều động nhân sự</h2>
                        @auth
                        @if (Auth::user()->hasPermissionOnPage('3', '13'))
                                <button data-bs-toggle="modal" data-bs-target="#addHrtransferModal"
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
    <form action="{{ route('hrtransfer.index.admin') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 mb-2 col-sm-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo tên"
                    value="{{ request('full_name') }}" name="full_name" id="">
            </div>
            <div class="col-6 mb-2 col-sm-3">
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
            <div class="col-6 mb-2 col-sm-3">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo chức vụ"
                    value="{{ request('position') }}" name="position" id="">
            </div>
            <div class="col-6 mb-2 col-sm-2">
                <input type="date" class="form-control" placeholder="Tìm kiếm theo ngày" value="{{ request('date') }}"
                    name="date" id="">
            </div>
            <div class="col-12 col-sm-2">
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
                                <tr>
                                    <th>Id</th>
                                    <th>Họ tên</th>
                                    <th>Chức vụ</th>
                                    <th>Ngày điều động</th>
                                    <th>Chi nhánh cũ</th>
                                    <th>Chi nhánh mới</th>
                                    <th>Lý do</th>
                                    <th>Trạng thái</th>
                                    @auth
                                    @if (Auth::user()->hasPermissionOnPage('8', '13'))
                                            <th>Phê duyệt</th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hrtransfer as $it)
                                    <tr>
                                        <td data-label="Id">{{ $it->id }}</td>
                                        <td data-label="Họ tên">{{ $it->full_name }}</td>
                                        <td data-label="Chức vụ">{{ $it->position }}</td>
                                        <td data-label="Ngày điều động">{{ $it->transfer_date }}</td>
                                        <td data-label="Chi nhánh cũ">{{ $it->old_branch_name }}</td>
                                        <td data-label="Chi nhánh mới">{{ $it->new_branch_name }}</td>
                                        <td data-label="Lý do">{{ $it->reason }}</td>
                                        @if ($it->status == 0)
                                            <td data-label="Trạng thái" class="text-danger">Chưa phê duyệt</td>
                                            @auth
                                            @if (Auth::user()->hasPermissionOnPage('8', '13'))
                                                    <td data-label="Phê duyệt" class=" text-center">
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#approveHrTransfer-{{ $it->id }}"><i
                                                                class="fas fa-toggle-off " style="font-size: 28px"></i></a>
                                                    </td>
                                                @endif
                                            @endauth
                                        @else
                                            <td data-label="Trạng thái" class="text-success">Đã phê duyệt</td>
                                            @if (Auth::user()->hasPermissionOnPage('8', '13'))
                                                <td data-label="Phê duyệt" class=" text-center"><i class="fas fa-toggle-on"
                                                        style="font-size: 28px; color:#009879"></i></td>
                                            @endif
                                        @endif

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $hrtransfer->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
        @if (Auth::user()->role_id == 3)
            <!-- Modal Thêm mới -->
            <div class="modal fade" id="addHrtransferModal" tabindex="-1" aria-labelledby="addHrtransferModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="addHrtransferModalLabel">Yêu cầu điều động nhân sự</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addEmployeeForm" action="{{ route('hrtransfer.create.admin') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="diachi" class="form-label">Lý do</label>
                                            <textarea type="text" class="form-control" name="reason" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Yêu cầu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @foreach ($hrtransfer->where('status', 0) as $it)
                <!-- Modal duyệt -->
                <div class="modal fade" id="approveHrTransfer-{{ $it->id }}" tabindex="-1"
                    aria-labelledby="approveHrTransferLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="approveHrTransferLabel">Điều động nhân sự</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addEmployeeForm" action="{{ route('hrtransfer.approve.admin', $it->id) }}"
                                    method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Chọn nhân viên</label>
                                        <select class="form-select" name="employee[]" aria-label="Default select example">
                                            <option selected>Chọn nhân viên</option>
                                            @foreach ($employee as $item)
                                                @if ($item->branch_id != $it->new_branch)
                                                    <option value="{{ $item }}">{{ $item->full_name }} Chi nhánh:
                                                        {{ $item->branch->branch_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ngày điều động</label>
                                        <input type="date" class="form-control" name="date" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Yêu cầu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endauth
@endsection
