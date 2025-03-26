<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chúc mừng sinh nhật</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .birthday-card {
      background-color: #fff;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 500px;
      width: 100%;
      animation: fadeIn 1s ease;
    }

    .birthday-card h1 {
      color: #e67e22;
      margin-bottom: 10px;
    }

    .birthday-card p {
      font-size: 18px;
      color: #333;
    }

    .birthday-list {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }

    .birthday-list li {
      font-weight: bold;
      font-size: 20px;
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .birthday-image {
      width: 150px;
      margin-top: 20px;
    }

    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }
  </style>
</head>
<body>

  <div class="birthday-card">
    <h2>🎉 Chúc mừng sinh nhật! 🎉</h2>
    <p>Hôm nay ngày {{ \Carbon\Carbon::now()->format('d/m/Y') }} là sinh nhật của:</p>
    <ul class="birthday-list">
      @foreach ($employees as $employee)
        <li>{{ $employee->full_name }}</li>
      @endforeach
    </ul>
    <img class="birthday-image" src="https://cdn-icons-png.flaticon.com/512/3595/3595455.png" alt="Birthday Icon">
  </div>

</body>
</html>
