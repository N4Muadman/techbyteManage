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
                        <li class="breadcrumb-item" aria-current="page">Đánh giá và khen thưởng</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Hiệu suất làm việc và đánh giá khen thưởng</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('evaluation.index.admin') }}" method="get">
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
            <div class="col-6 col-sm-3 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo chức vụ"
                    value="{{ request('position') }}" name="position" id="">
            </div>
            <div class="col-6 col-sm-2 mb-2">
                <input type="month" class="form-control" placeholder="Tìm kiếm theo tháng" value="{{ request('month') }}"
                    name="month" id="">
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
                                    <th>ID</th>
                                    <th>Họ tên</th>
                                    <th>Chi nhánh</th>
                                    <th>Chức vụ</th>
                                    <th>Tổng giờ làm</th>
                                    <th>Tổng số lần đi muộn</th>
                                    <th>Tổng giờ đi muộn</th>
                                    <th>Tổng dự án</th>
                                    <th>Tổng doanh thu</th>
                                    <th>Chức năng / khen thưởng</th>
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
                                @endphp
                                @foreach ($performance as $it)
                                    <tr>
                                        <td>{{ $it->id }}</td>
                                        <td>{{ $it->employee?->full_name }}</td>
                                        <td>{{ $it->employee?->branch?->branch_name }}</td>
                                        <td>{{ $it->employee?->position }}</td>
                                        <td>{{ formatHour($it->total_work) }}</td>
                                        <td class="text-danger">{{ $it->total_late_arrivals ?? 0 }}</td>
                                        <td class="text-danger">{{ formatHour($it->total_late_hours) }}</td>
                                        <td>{{ $it->total_project ?? 0 }}</td>
                                        <td>{{ number_format($it->total_revenue, 0, '.', ',') . ' đ' }}</td>
                                        @if (!$it->performance_review)
                                            <td>
                                                @if (Auth::user()->hasPermissionOnPage('3', '8'))
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#addEvaluation-{{ $it->id }}"><i
                                                            class="fas fa-plus"></i></a>
                                                @endif
                                            </td>
                                        @else
                                            <td>
                                                @if (Auth::user()->hasPermissionOnPage('5', '8'))
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#detailEvaluation-{{ $it->id }}"><i
                                                            class="fas fa-info-circle me-5"></i></a>
                                                @endif
                                                @if (Auth::user()->hasPermissionOnPage('4', '8'))
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editEvaluation-{{ $it->id }}"><i
                                                            class="ti ti-edit f-18"></i></i></a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $performance->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($performance as $item)
        @if (!$item->performance_review)
            <div class="modal fade" id="addEvaluation-{{ $item->id }}" tabindex="-1"
                aria-labelledby="addEvaluationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="addEvaluationModalLabel">Đánh giá và khen thưởng nhân viên:
                                {{ $item->employee?->full_name }}</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addEvaluationForm" action="{{ route('evaluation.create.admin') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" value="{{ $item->id }}"
                                                name="work_performance_id" hidden>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Tổng dự án</label>
                                            <input type="number" class="form-control" placeholder="Nhập tổng số dự án"
                                                name="total_project" value="{{ $item->total_project }}">
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Tổng doanh thu</label>
                                            <input type="text" class="form-control money"
                                                value="{{ $item->total_revenue }}" placeholder="Nhập tổng số doanh thu"
                                                name="total_revenue">
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Điểm thời gian làm việc</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                placeholder="Nhập từ 0 đến 100" name="attendance_score">
                                            <span class="input-percent">%</span>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Điểm chất lượng công việc</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                placeholder="Nhập từ 0 đến 100" name="quality_score">
                                            <span class="input-percent">%</span>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Điểm năng suất làm việc</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                placeholder="Nhập từ 0 đến 100" name="productivity_score">
                                            <span class="input-percent">%</span>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Điểm giải quyết vấn đề</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                placeholder="Nhập từ 0 đến 100" name="problem_solving_score">
                                            <span class="input-percent">%</span>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Kết quả đánh giá</label>
                                            <input type="text" class="form-control" name="evaluation_result">
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Phần thưởng nếu có</label>
                                            <input type="text" class="form-control" name="reward">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Đánh giá</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="modal fade" id="editEvaluation-{{ $item->id }}" tabindex="-1"
                aria-labelledby="addEvaluationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="addEvaluationModalLabel">Đánh giá và khen thưởng nhân viên:
                                {{ $item->employee?->full_name }}</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addEvaluationForm" action="{{ route('evaluation.update.admin', $item->id) }}"
                                method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" value="{{ $item->id }}"
                                                name="work_performance_id" hidden>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Tổng dự án</label>
                                            <input type="number" class="form-control" placeholder="Nhập tổng số dự án"
                                                name="total_project" value="{{ $item->total_project }}">
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Tổng doanh thu</label>
                                            <input type="text" class="form-control money"
                                                value="{{ $item->total_revenue }}" placeholder="Nhập tổng số doanh thu"
                                                name="total_revenue">
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Điểm thời gian làm việc</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                placeholder="Nhập từ 0 đến 100" name="attendance_score"
                                                value="{{ $item->performance_review->attendance_score }}">
                                            <span class="input-percent">%</span>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Điểm chất lượng công việc</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                placeholder="Nhập từ 0 đến 100" name="quality_score"
                                                value="{{ $item->performance_review->quality_score }}">
                                            <span class="input-percent">%</span>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Điểm năng suất làm việc</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                placeholder="Nhập từ 0 đến 100" name="productivity_score"
                                                value="{{ $item->performance_review->productivity_score }}">
                                            <span class="input-percent">%</span>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Điểm giải quyết vấn đề</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                placeholder="Nhập từ 0 đến 100" name="problem_solving_score"
                                                value="{{ $item->performance_review->problem_solving_score }}">
                                            <span class="input-percent">%</span>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Kết quả đánh giá</label>
                                            <input type="text" class="form-control" name="evaluation_result"
                                                value="{{ $item->performance_review->evaluation_result }}">
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Phần thưởng nếu có</label>
                                            <input type="text" class="form-control" name="reward"
                                                value="{{ $item->performance_review->reward }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Chỉnh sửa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailEvaluation-{{ $item->id }}" tabindex="-1"
                aria-labelledby="addEvaluationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="addEvaluationModalLabel">Chi tiết đánh giá</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Họ tên:</p>
                                                    <span class="fw-bold">{{ $item->employee?->full_name }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Chi nhánh:</p>
                                                    <span
                                                        class="fw-bold">{{ $item->employee?->branch?->branch_name }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Chức vụ:</p>
                                                    <span class="fw-bold">{{ $item->employee?->position }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Tổng giờ làm:</p>
                                                    <span class="fw-bold">{{ formatHour($item->total_work) }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Tổng lần đi muộn:</p>
                                                    <span
                                                        class="fw-bold text-danger">{{ $item->total_late_arrivals ?? 0 }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Tổng giờ đi muộn:</p>
                                                    <span
                                                        class="fw-bold text-danger">{{ formatHour($item->total_late_hours) }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Tổng dữ án đã làm:</p>
                                                    <span class="fw-bold">{{ $item->total_project ?? 0 }} dự án</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Tổng doanh thu:</p>
                                                    <span
                                                        class="fw-bold">{{ number_format($item->total_revenue, 0, '.', ',') . ' đ' }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Thời gian làm việc:</p>
                                                    <span
                                                        class="fw-bold">{{ $item->performance_review->attendance_score . '%' }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Chất lượng công việc:</p>
                                                    <span
                                                        class="fw-bold">{{ $item->performance_review->quality_score . '%' }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Năng suất làm việc:</p>
                                                    <span
                                                        class="fw-bold">{{ $item->performance_review->productivity_score . '%' }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Giải quyết vấn đề:</p>
                                                    <span
                                                        class="fw-bold">{{ $item->performance_review->problem_solving_score . '%' }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Điểm trung bình:</p>
                                                    <span
                                                        class="fw-bold">{{ $item->performance_review->overall_score . '%' }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Kết quả đánh giá:</p>
                                                    <span
                                                        class="fw-bold">{{ $item->performance_review->evaluation_result ?? 'Không' }}</span>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <p class="text-worker-profile">Phần thưởng:</p>
                                                    <span
                                                        class="fw-bold">{{ $item->performance_review->reward ?? 'Không' }}</span>
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
        @endif
    @endforeach
@endsection
