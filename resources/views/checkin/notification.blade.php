@extends('layout')
@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: 100%">
        <!-- Success Card -->
        @if ($notification == 'success')
            <div class="card success-card">
                <div class="icon success-icon">
                    <i class="fas fa-check-circle mb-3" style="font-size: 80px; color: #2ca87f"></i>
                </div>
                <h3>Thành công</h3>
                </h3>
                <p>{{ $message }}</p>
                <a href="{{ route('home.admin') }}" class="btn btn-success btn-custom">Tiếp tục</a>
            </div>
        @else
            <div class="card error-card">
                <div class="icon error-icon">
                    <i class="fas fa-times-circle mb-3" style="font-size: 80px; color: #dc2626"></i>
                </div>
                <h3>Thất bại</h3>
                <p>{{ $message }}</p>
                <form action="{{ route('qrcode-checkin') }}" method="post">
                    @csrf
                    <button class="btn btn-danger btn-custom" type="submit">Thử lại</button>
                </form>
            </div>
        @endif

    </div>
@endsection
