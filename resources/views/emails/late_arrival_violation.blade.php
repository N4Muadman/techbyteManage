<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông báo vi phạm đi muộn</title>
    <style>
        body {
            font-family: "Segoe UI", Roboto, sans-serif;
            background-color: #f9f9f9;
            /* padding: 10px; */
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #c0392b;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .summary {
            background: #f2f2f2;
            padding: 10px 15px;
            border-left: 4px solid #e74c3c;
            margin-bottom: 20px;
        }

        .footer {
            font-size: 13px;
            color: #777;
            margin-top: 30px;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    @php
        if (!function_exists('formatHour')) {
            function formatHour($totalHours)
            {
                $hours = floor($totalHours);
                // Tính phần phút
                $minutes = ($totalHours - $hours) * 60;
                $minutes = round($minutes);
                return $hours . ' giờ ' . $minutes . ' phút';
            }
        }
    @endphp
    <div class="email-container">
        <h2>THÔNG BÁO VI PHẠM ĐI MUỘN</h2>

        <p>Xin chào <strong>{{ $employee->full_name }}</strong>,</p>

        <p>Bộ phận Nhân sự ghi nhận bạn đã vi phạm quy định về giờ làm việc của công ty trong
            <strong>{{ $type }}</strong>.
        </p>

        <div class="summary">
            <p><strong>Tổng số lần đi muộn:</strong> {{ $totalLateTimes }} lần</p>
            <p><strong>Tổng số giờ đi muộn:</strong> {{ formatHour($totalLateHours) }}</p>
        </div>

        <p>Theo chính sách công ty, nhân viên đi muộn quá <strong>30 phút/tuần</strong> hoặc <strong>60
                phút/tháng</strong> sẽ bị ghi nhận vi phạm. Đề nghị bạn nghiêm túc rút kinh nghiệm và cải thiện thời
            gian làm việc đúng quy định.</p>

        <p>Trân trọng,<br>Phòng Nhân sự</p>

        <div class="footer">
            (Email được gửi vào lúc {{ \Carbon\Carbon::now()->format('H:i \n\g\à\y d/m/Y') }})
        </div>
    </div>
</body>

</html>
