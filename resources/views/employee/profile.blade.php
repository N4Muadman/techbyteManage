@extends('layout')

@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-4">Thông Tin Cá Nhân</h2>
        <div class="row g-4">
            <!-- Thông tin cá nhân -->
            <div class="col-md-12">
                <div class="card shadow rounded-4 p-4">
                    <form method="POST" action="{{ route('employee.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="text-center mb-4">
                            <label for="upload-avatar" class="form-label d-block">Ảnh đại diện</label>
                            <label for="upload-avatar" class="cursor-pointer">
                                <img id="avatar-preview" src="{{ $employee->avatar ? '/storage/' .$employee->avatar :'/images/avatar.jpg' }}"
                                    class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                            </label>

                            <input type="file" name="avatar" id="upload-avatar" class="form-control d-none"
                                accept="image/*">

                            @error('avatar')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" name="full_name"
                                    class="form-control @error('full_name') border-danger @enderror"
                                    value="{{ $employee->full_name }}" required>
                                @error('full_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') border-danger @enderror"
                                    value="{{ $employee->email }}" required>

                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone_number"
                                    class="form-control @error('phone_number') border-danger @enderror"
                                    value="{{ $employee->phone_number }}" required>
                                @error('phone_number')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" name="date_of_birth"
                                    class="form-control @error('date_of_birth') border-danger @enderror"
                                    value="{{ $employee->date_of_birth }}" required>
                                @error('date_of_birth')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select name="gender" class="form-select @error('gender') border-danger @enderror">
                                    <option value="Nam" {{ $employee->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ $employee->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                </select>
                                @error('gender')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Ngày bắt đầu làm việc</label>
                                <input type="date" class="form-control" value="{{ $employee->start_date }}" disabled>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Chức vụ</label>
                                <input type="text" class="form-control" value="{{ $employee->position }}" disabled>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Lương cơ bản</label>
                                <input type="number" class="form-control" value="{{ $employee->base_salary }}" disabled>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Chi nhánh</label>
                                <input type="text" class="form-control" value="{{ $employee->branch->branch_name }}"
                                    disabled>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('upload-avatar').addEventListener('change', (event) => {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('avatar-preview');
                preview.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        })
    </script>
@endsection
