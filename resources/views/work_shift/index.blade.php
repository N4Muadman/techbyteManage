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
                        <li class="breadcrumb-item" aria-current="page">Quản lý ca làm</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách ca làm</h2>
                        @if (Auth::user()->hasPermissionOnPage('1', '6'))
                            <button data-bs-toggle="modal" data-bs-target="#addWork_shiftModal"
                                class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i> Add
                                new
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
                                    <th>Tên ca</th>
                                    <th>Giờ bắt đầu</th>
                                    <th>Giờ kết thúc</th>
                                    @if (Auth::user()->hasPermissionOnPage('2', '6') || Auth::user()->hasPermissionOnPage('4', '6'))
                                    <th>Chức năng</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($work_shift as $it)
                                    <tr>
                                        <td data-label="Tên ca">{{ $it->name }}</td>
                                        <td data-label="Giờ bắt đầu">{{ $it->start_time }}</td>
                                        <td data-label="Giờ kết thúc">{{ $it->end_time }}</td>
                                        <td data-label="Chức năng">
                                            @if (Auth::user()->hasPermissionOnPage('2', '6'))
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#editWork_shiftModal-{{ $it->id }}"
                                                    class="employee-edit me-3"><i class="fas fa-edit"></i></a>
                                            @endif

                                            @if (Auth::user()->hasPermissionOnPage('4', '6'))
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#deleteWork_shiftModal-{{ $it->id }}"><i
                                                        class="ti ti-trash f-20"></i></a>
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

    <!-- Modal create -->
    <div class="modal fade" id="addWork_shiftModal" tabindex="-1" aria-labelledby="addWork_shiftModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addWork_shiftModalLabel">Thêm mới ca làm</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('work_shift.create.admin') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-lable">Tên ca</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-lable">Giờ bắt đầu</label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-lable">Giờ kết thúc</label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @foreach ($work_shift as $it)
        <!-- Modal Xóa -->
        <div class="modal fade" id="deleteWork_shiftModal-{{ $it->id }}" tabindex="-1"
            aria-labelledby="deleteWork_shiftModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteWork_shiftModalLabel">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có muốn xóa ca làm <strong>{{ $it->name }}</strong> không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form action="{{ route('work_shift.delete.admin', $it->id) }}" method="post"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal create -->
        <div class="modal fade" id="editWork_shiftModal-{{ $it->id }}" tabindex="-1"
            aria-labelledby="editWork_shiftModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="editWork_shiftModalLabel">Thêm mới ca làm</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('work_shift.update.admin', $it->id) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-lable">Tên ca</label>
                                <input type="text" name="name" value="{{ $it->name }}" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-lable">Giờ bắt đầu</label>
                                <input type="time" name="start_time" value="{{ $it->start_time }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-lable">Giờ kết thúc</label>
                                <input type="time" name="end_time" value="{{ $it->end_time }}" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Sửa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
