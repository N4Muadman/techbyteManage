<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lỗi không có quyền truy cập</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #e0e7ff;
        }

        .error-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 55%;
        }

        .error-code {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 250px;
            color: #7f8ff4;
            font-weight: bold;
        }


        .error-code .ufo {
            position: relative;
            width: 180px;
            height: 160px;
            margin: 0 10px;
            background: url('/images/ufo.png') center/cover no-repeat; /* Add your UFO image here */
            animation: float 2s infinite ease-in-out;
        }

        .error-code .ufo::before {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 10px;
            background: rgba(127, 143, 244, 0.2);
            border-radius: 50%;
            animation: beam 2s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes beam {
            0%, 100% {
                opacity: 0.3;
            }
            50% {
                opacity: 0.7;
            }
        }

        .message {
            color: #6b7280;
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .home-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #7f8ff4;
            color: #fff;
            text-decoration: none;
            border-radius: 30px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .home-button:hover {
            background-color: #6670e1;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">
            <span>4</span>
            <div class="ufo"></div>
            <span>3</span>
        </div>
        <div class="message">
            <h1 style="color: #6b7280">Lỗi 403</h1>
            Bạn không có quyền truy cập vào trang trước đó!
        </div>
        <a href="{{ route('home.admin') }}" class="home-button">Trở vể trang chủ</a>
    </div>
</body>
</html>
