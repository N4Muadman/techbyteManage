@extends('layout')

@section('content')
    <div class="container text-center">
        <div class="row justify-content-center align-items-center mt-5">
            <div class="col-sm-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">Thay đổi mật khẩu</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('change-password') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu cũ</label>
                                <input type="password" class="form-control" name="oldPassword" required>
                            </div>
                            @if (session()->has('wrongPassword'))
                                <div class="text-danger mb-3">{{ session('wrongPassword') }}</div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            @if ($errors->has('password'))
                                <div class="text-danger mb-3">{{ $errors->first('password') }}</div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Xác nhận lại mật khẩu</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                            @endif

                            <button type="submit" class="btn btn-info">Đổi mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
