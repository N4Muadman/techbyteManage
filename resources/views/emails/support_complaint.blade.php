<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hỗ trợ và khiếu nại</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
            color: #333;
        }
        .container {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        h2 {
            color: #0066cc;
        }
        .content {
            margin-top: 15px;
            line-height: 1.6;
        }
        .info {
            margin-top: 10px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Yêu cầu hỗ trợ / khiếu nại mới</h2>

        <p>Xin chào bộ phận hỗ trợ,</p>

        <div class="info">
            <p><strong>Người gửi:</strong> {{ $complaint?->employee?->full_name }}</p>
            <p><strong>Loại phản hồi:</strong> {{ $complaint->complaint_type }}</p>
        </div>

        <div class="content">
            <p><strong>Nội dung:</strong></p>
            <blockquote>
                {{ $complaint->description }}
            </blockquote>
        </div>

        <p>Vui lòng kiểm tra và phản hồi trong thời gian sớm nhất.</p>

        <div class="footer">
            <p>Đây là email tự động từ hệ thống hỗ trợ khách hàng. Vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>
