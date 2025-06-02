@extends('layout')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Hỗ trợ / khiếu nại</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách hỗ trợ khiếu nại</h2>
                        @auth
                            @if (Auth::user()->hasPermissionOnPage('1', '14'))
                                <button data-bs-toggle="modal" data-bs-target="#addComplaintModal"
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
                                    <th>Loại yêu cầu</th>
                                    <th>Mô tả</th>
                                    <th>Trạng thái</th>
                                    @if (Auth::user()->hasPermissionOnPage('6', '14'))
                                        <th>Phê duyệt</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($complaint as $it)
                                    <tr>
                                        <td data-label="Id">{{ $it->id }}</td>
                                        <td data-label="Họ tên">{{ $it->employee->full_name }}</td>
                                        <td data-label="Chức vụ">{{ $it->employee->position }}</td>
                                        <td data-label="Chi nhánh">{{ $it->employee->branch->branch_name }}</td>
                                        <td data-label="Loại yêu cầu">{{ $it->complaint_type }}</td>
                                        <td data-label="Mô tả">{{ $it->description }}</td>
                                        @if ($it->status == 0)
                                            <td data-label="Trạng thái" class="text-danger">Đang chờ phê duyệt</td>
                                            @if (Auth::user()->hasPermissionOnPage('6', '14'))
                                                <td data-label="Phê duyệt" class=" text-center">
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#approveComplaint-{{ $it->id }}"><i
                                                            class="fas fa-toggle-off " style="font-size: 28px"></i></a>
                                                </td>
                                            @endif
                                        @else
                                            <td data-label="Trạng thái" class="text-success">Đã được phê duyệt</td>
                                            @if (Auth::user()->hasPermissionOnPage('6', '14'))
                                                <td data-label="Phê duyệt" class=" text-center"><i class="fas fa-toggle-on"
                                                        style="font-size: 28px; color:#009879"></i></td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $complaint->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addComplaintModal" tabindex="-1" aria-labelledby="addComplaintModalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addComplaintModalModalLabel">Yêu cầu hỗ trợ / khiếu nại
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addComplaintModalForm" action="{{ route('complaint.create.admin') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Loại yêu cầu</label>
                                    <select class="form-select" name="complaint_type" id="">
                                        <option value="" selected>Chọn loại yêu cầu</option>
                                        <option value="Thời gian chấm công">Thời gian chấm công</option>
                                        <option value="Tính lương">Tính lương</option>
                                        <option value="Lịch làm việc">Lịch làm việc</option>
                                        <option value="Khác">Khác</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả yêu cầu</label>
                                    <textarea class="form-control" name="description" id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($complaint->where('status', 0) as $it)
        <!-- Modal duyệt -->
        <div class="modal fade" id="approveComplaint-{{ $it->id }}" tabindex="-1"
            aria-labelledby="approveComplaintLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveComplaintLabel">Xác nhận phê duyệt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có muốn phê duyệt yêu cầu hỗ trợ khiếu nại cho nhân viên
                            <strong>{{ $it->employee->full_name }}</strong> không?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form action="{{ route('complaint.approve.admin', $it->id) }}" method="post"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Phê duyệt</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
