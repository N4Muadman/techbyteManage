<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Tham Gia Dự Án</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .email-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .email-header h1 {
            font-size: 2.5em;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .email-header .subtitle {
            font-size: 1.2em;
            opacity: 0.9;
            font-weight: 300;
        }

        .email-meta {
            background: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
        }

        .meta-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .meta-label {
            font-weight: 600;
            color: #555;
            min-width: 80px;
            font-size: 14px;
        }

        .meta-value {
            color: #333;
            font-size: 14px;
        }

        .email-content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 1.1em;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        .content-section {
            margin-bottom: 30px;
        }

        .content-section h2 {
            color: #667eea;
            font-size: 1.4em;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
            position: relative;
        }

        .content-section h2::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .highlight-box {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);
            border-left: 4px solid #4facfe;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .project-details {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e9ecef;
        }

        .project-details h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .detail-item {
            display: flex;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
        }

        .detail-value {
            color: #333;
            flex: 1;
        }

        .team-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .team-member {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            text-align: center;
            transition: all 0.3s ease;
        }

        .team-member:hover {
            border-color: #4facfe;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.2);
        }

        .member-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .member-role {
            color: #666;
            font-size: 0.9em;
        }

        .action-buttons {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 0 10px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .email-footer {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .footer-content {
            margin-bottom: 20px;
        }

        .company-info {
            font-size: 0.9em;
            opacity: 0.8;
            line-height: 1.8;
        }

        .contact-info {
            margin-top: 15px;
            font-size: 0.85em;
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .email-container {
                border-radius: 8px;
            }

            .email-content {
                padding: 20px 15px;
            }

            .email-header {
                padding: 20px 15px;
            }

            .email-header h1 {
                font-size: 2em;
            }

            .team-list {
                grid-template-columns: 1fr;
            }

            .btn {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎉 Chào Mừng Tham Gia Dự Án!</h1>
            <div class="subtitle">Thông báo chính thức về việc tham gia dự án mới</div>
        </div>

        <div class="email-meta">
            <div class="meta-row">
                <span class="meta-label">Từ:</span>
                <span class="meta-value">Phòng Nhân Sự Techbyte</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Đến:</span>
                <span class="meta-value">Nhân viên dự án</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Ngày:</span>
                <span class="meta-value">{{now()->format('d/m/Y')}}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Chủ đề:</span>
                <span class="meta-value">Thông báo đã được tham gia dự án - <strong>{{$project->name}}</strong></span>
            </div>
        </div>

        <div class="email-content">
            <div class="greeting">
                Kính chào <strong>{{$employee->full_name}}</strong>,
            </div>

            <div class="content-section">
                <p>Chúng tôi rất vui mừng thông báo rằng bạn đã được chọn tham gia vào dự án quan trọng của công ty. Đây là một cơ hội tuyệt vời để bạn phát triển kỹ năng và đóng góp vào sự thành công chung của tổ chức.</p>
            </div>

            <div class="highlight-box">
                <strong>🎯 Lời chúc mừng!</strong><br>
                Bạn chính thức trở thành thành viên của dự án. Chúng tôi tin tưởng vào khả năng và kinh nghiệm của bạn sẽ mang lại những đóng góp có giá trị cho dự án này.
            </div>

            <div class="content-section">
                <h2>📋 Thông Tin Dự Án</h2>
                <div class="project-details">
                    <h3>Chi Tiết Dự Án</h3>
                    <div class="detail-item">
                        <span class="detail-label">Tên dự án:</span>
                        <span class="detail-value"><a href="{{route('projects.index')}}">{{$project->name}}</a></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Thời gian:</span>
                        <span class="detail-value">{{$project->start_date}} - {{$project->end_date}}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Vai trò của bạn: </span>
                        <span class="detail-value">{{$type}}</span>
                    </div>
                </div>
            </div>

            {{-- <div class="content-section">
                <h2>👥 Đội Ngũ Dự Án</h2>
                <p>Bạn sẽ làm việc cùng với đội ngũ tài năng sau:</p>
                <div class="team-list">
                    <div class="team-member">
                        <div class="member-name">Nguyễn Văn A</div>
                        <div class="member-role">Project Manager</div>
                    </div>
                    <div class="team-member">
                        <div class="member-name">Trần Thị B</div>
                        <div class="member-role">Technical Lead</div>
                    </div>
                    <div class="team-member">
                        <div class="member-name">Lê Văn C</div>
                        <div class="member-role">Business Analyst</div>
                    </div>
                    <div class="team-member">
                        <div class="member-name">Phạm Thị D</div>
                        <div class="member-role">Designer</div>
                    </div>
                </div>
            </div> --}}

            <div class="content-section">
                <p>Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi qua email hoặc điện thoại. Chúng tôi mong muốn được làm việc cùng bạn trong dự án thú vị này!</p>
            </div>

            <div style="margin-top: 30px; font-style: italic; color: #666;">
                Trân trọng,<br>
                <strong>Ban Quản Lý Dự Án</strong><br>
                <strong>CÔNG TY TNHH ĐẦU TƯ CÔNG NGHỆ TECHBYTE</strong>
            </div>
        </div>

        <div class="email-footer">
            <div class="footer-content">
                <div class="company-info">
                    <strong>CÔNG TY TNHH ĐẦU TƯ CÔNG NGHỆ TECHBYTE</strong><br>
                    Website: [hotrodoan.com] | Email: [ai@idai.vn]
                </div>
                <div class="contact-info">
                    Email này được gửi đến bạn như một thông báo chính thức về dự án.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
