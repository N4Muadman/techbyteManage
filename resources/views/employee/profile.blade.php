@extends('layout')
@section('content')
    <h2 class="title text-center mb-5">Thông tin cá nhân</h2>
    <div class="profile-info">
        <div class="row">
            <div class="col-12 col-md-6 d-flex justify-content-sm-end mb-5">
                <div class="col-sm-6 col-12">
                    <label>Họ tên:</label>
                <span>{{ $employee->full_name }}</span>

                <label>Email:</label>
                <span>{{ $employee->email }}</span>

                <label>Số Điện Thoại:</label>
                <span>{{ $employee->phone_number }}</span>

                <label>Ngày sinh:</label>
                <span>{{ $employee->date_of_birth }}</span>

                <label>Giới tính:</label>
                <span>{{ $employee->gender }}</span>

                <label>Ngày bắt đầu làm việc:</label>
                <span>{{ $employee->start_date }}</span>

                <label>Chức vụ:</label>
                <span>{{ $employee->position }}</span>

                <label>Lương cơ bản:</label>
                <span>{{ $employee->base_salary }}</span>

                <label>Chi nhánh:</label>
                <span>{{ $employee->branch->branch_name }}</span>
                </div>
            </div>
            <div class="col-12 col-md-6 ">
                @if ($salary)
                    <label>Phụ cấp:</label>
                    <span>{{ number_format($salary->allowance) .' đ' }}</span>

                    <label>Thưởng:</label>
                    <span>{{ number_format($salary->bonus) .' đ' }}</span>

                    <label>Khấu trừ:</label>
                    <span>{{ number_format($salary->deductions) .' đ' }}</span>

                    <label>Ngày tạo lương:</label>
                    <span>{{ $salary->salary_date }}</span>

                    <label>Mô tả:</label>
                    <span>{{ $salary->description }}</span>
                @else
                    Lương thưởng của bạn chưa có trong tháng nay
                @endif
            </div>
        </div>
    </div>
    {{-- <div class="profile-buttons">
        <button>Cập Nhật Thông Tin</button>
    </div> --}}
@endsection
