@extends('layout')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Khách hàng doanh nghiệp</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">Công nợ doanh nghiệp</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-2">Danh sách công nợ</h2>
                    @if (request('start_date') && request('end_date'))
                        <p class="f-18">Thống kê từ ngày: <strong>{{ request('start_date') }}</strong> đến:
                            <strong>{{ request('end_date') }}</strong>
                        </p>
                    @elseif (request('start_date') && !request('end_date'))
                        <p class="f-18">Thống kê từ ngày: <strong>{{ request('start_date') }}</strong> đến hết tháng
                            nay</p>
                    @elseif (!request('start_date') && request('end_date'))
                        <p class="f-18">Thống kê từ đầu tháng nay đến: <strong>{{ request('end_date') }}</strong></p>
                    @else
                        <p class="f-18">Thống kê theo <strong>tháng nay</strong></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('debtContract') }}" method="get">
    <div class="row">
        <div class="mb-3 col-3">
            <input type="date" class="form-control" title="Ngày bắt đầu" name="start_date"
                value="{{ request('start_date') }}">
        </div>
        <div class="mb-3 col-3">
            <input type="date" class="form-control" title="Ngày kết thúc" name="end_date"
                value="{{ request('end_date') }}">
        </div>
        <div class="mb-3 col-3">
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
                                <th>Họ tên</th>
                                <th>Nhân viên tư vấn</th>
                                <th>Mã hợp đồng</th>
                                <th>Ngày ký hợp đồng</th>
                                <th>Giá trị hợp đồng</th>
                                <th>Số tiền tạm ứng</th>
                                <th>Số tiền còn thiếu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($debts as $it)
                                <tr>
                                    <td>{{ $it->customer->employee->full_name }}</td>
                                    <td>{{ $it->customer->full_name }}</td>
                                    <td>{{ $it->contract_code }}</td>
                                    <td>{{ $it->date }}</td>
                                    <td>{{ number_format($it->contract_value, 0, '.', ',') }}</td>
                                    <td>{{ number_format($it->advance_money, 0, '.', ',') }}</td>
                                    <td>{{ number_format($it->contract_value - $it->advance_money, 0, '.', ',') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="ps-5 pe-5">
                        {{ $debts->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
