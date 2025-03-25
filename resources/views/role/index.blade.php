@extends('layout')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quản lý phần quyền</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Quản lý vai trò</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách vai trò</h2>
                        <button data-bs-toggle="modal" data-bs-target="#add-role"
                            class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i> Thêm
                            mới vai trò</button>
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
                                    <th>Tên</th>
                                    <th>Mô tả</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $it)
                                    <tr>
                                        <td>{{ $it->id }}</td>
                                        <td>{{ $it->name }}</td>
                                        <td>{{ $it->description }}</td>
                                        <td>
                                            <a href="#!" data-bs-toggle="modal"
                                                class="avtar avtar-xs btn-link-secondary"
                                                data-bs-target="#edit-role-{{ $it->id }}"><i
                                                    class="ti ti-edit f-18"></i></a>
                                            <a href="#!" data-bs-toggle="modal"
                                                class="avtar avtar-xs btn-link-secondary"
                                                data-bs-target="#delete-role-{{ $it->id }}"><i
                                                    class="ti ti-trash f-18"></i></a>
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

    @foreach ($roles as $it)
        <!-- Modal delete -->
        <div class="modal fade" id="delete-role-{{ $it->id }}" tabindex="-1" aria-labelledby="deleteLeaveLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteLeaveLabel">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có muốn xóa vai trò <strong>{{ $it->name }}</strong>
                            không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form action="{{ route('roles.destroy', $it->id) }}" method="post" style="display: inline;">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-role-{{ $it->id }}" tabindex="-1" aria-labelledby="addLeaveModalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Chỉnh sửa vai trò</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addLeaveModalForm" action="{{ route('roles.update', $it->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Tên vai trò</label>
                                        <input type="text" class="form-control" name="name" placeholder="Nhập tên vai trò" value="{{ $it->name }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mô tả</label>
                                        <textarea name="description" class="form-control" cols="10" rows="3" placeholder="Nhập mô tả">{{ $it->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal add -->
    <div class="modal fade" id="add-role" tabindex="-1" aria-labelledby="addLeaveModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addLeaveModalModalLabel">Thêm mới vai trò</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addLeaveModalForm" action="{{ route('roles.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Tên vai trò</label>
                                    <input type="text" class="form-control" name="name" placeholder="Nhập tên vai trò"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control" cols="10" rows="3" placeholder="Nhập mô tả"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
