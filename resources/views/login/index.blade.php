<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="{{asset('css/styleLogin.css')}}">
</head>
<body>
<div class="login-container">
    <h2>Đăng nhập</h2>
    <a href="{{ route('download-file') }}" class="btn">Nhân sự phải đọc</a>
    <form action="{{route('login')}}" method="post" style="margin-top: 20px">
        @csrf
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
        </div>
        @if(session()->has('errorLogin'))
            <p class="error_Login">{{session('errorLogin')}}</p>
        @endif
        <button type="submit" class="login-button">Đăng nhập</button>
    </form>
</div>
</body>
</html>
