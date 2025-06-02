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
                        <li class="breadcrumb-item" aria-current="page">Quản lý chi nhánh</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách chi nhánh</h2>

                        @if (Auth::user()->hasPermissionOnPage('1', '3'))
                            <button data-bs-toggle="modal" data-bs-target="#addBranchModal"
                                class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i> Add new
                                item</button>
                        @endif
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
                                    <th>Tên chi nhánh</th>
                                    <th>Địa chỉ</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ IP</th>
                                    <th>Trạng thái</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branches as $it)
                                    <tr>
                                        <td data-label="Id">{{ $it->id }}</td>
                                        <td data-label="Tên chi nhánh">{{ $it->branch_name }}</td>
                                        <td data-label="Địa chỉ">{{ $it->address }}</td>
                                        <td data-label="Số điện thoại">{{ $it->phone_number }}</td>
                                        <td data-label="Số điện thoại">{{ $it->address_ip }}</td>
                                        @if ($it->status == 0)
                                            <td data-label="Trạng thái" class="text-danger">Không hoạt động</td>
                                        @else
                                            <td data-label="Trạng thái" class="text-success">Đang hoạt động</td>
                                        @endif
                                        <td data-label="Chức năng">
                                            @if (Auth::user()->hasPermissionOnPage('2', '3'))
                                                <a href="#" style="margin-right: 10px" data-bs-toggle="modal"
                                                data-bs-target="#editBranchModal-{{ $it->id }}"><i
                                                    class="fas fa-edit"></i></a>
                                            @endif
                                            @if (Auth::user()->hasPermissionOnPage('4', '3'))
                                                <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteBranchModal-{{ $it->id }}"><i
                                                    class="ti ti-trash f-20"></i></a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $branches->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addBranchModal" tabindex="-1" aria-labelledby="addBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addBranchModalLabel">Thêm mới chi nhánh</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBranchForm" action="{{ route('branch.create.admin') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="hoten" class="form-label">Tên chi nhánh</label>
                                    <input type="text" class="form-control" name="branch_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ngaysinh" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" name="address" required>
                                </div>
                                <div class="mb-3">
                                    <label for="diachi" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="diachi" class="form-label">Địa chỉ IP</label>
                                    <input type="text" class="form-control" name="address_ip" required>
                                </div>
                                <div class="mb-3">
                                    <label for="diachi" class="form-label">Trạng thái</label>
                                    <select class="form-select" name="status" id="">
                                        <option value="1" selected>Đang hoạt động</option>
                                        <option value="0">Không hoạt động</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($branches as $it)
        <div class="modal fade" id="editBranchModal-{{ $it->id }}" tabindex="-1"
            aria-labelledby="editBranchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="editBranchModalLabel">Chỉnh sửa chi nhánh</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addBranchForm" action="{{ route('branch.update.admin', $it->id) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="hoten" class="form-label">Tên chi nhánh</label>
                                        <input type="text" class="form-control" name="branch_name"
                                            value="{{ $it->branch_name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ngaysinh" class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address"
                                            value="{{ $it->address }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="diachi" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone_number"
                                            value="{{ $it->phone_number }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="diachi" class="form-label">Địa chỉ IP</label>
                                        <input type="text" class="form-control" name="address_ip" value="{{ $it->address_ip }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="diachi" class="form-label">Trạng thái</label>
                                        <select class="form-select" name="status" id="">
                                            @if ($it->status == 0)
                                                <option value="0" selected>Không hoạt động</option>
                                                <option value="1">Đang hoạt động</option>
                                            @else
                                                <option value="1" selected>Đang hoạt động</option>
                                                <option value="0">Không hoạt động</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Chỉnh sửa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- dialog delete --}}

        <div class="modal fade" id="deleteBranchModal-{{ $it->id }}" tabindex="-1"
            aria-labelledby="deleteBranchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteBranchModalLabel">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có muốn xóa chi nhánh <strong>{{ $it->branch_name }}</strong> không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form action="{{ route('branch.delete.admin', $it->id) }}" method="post"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
