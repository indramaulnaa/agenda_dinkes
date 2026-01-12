<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Agenda Dinkes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f0fdf4; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(25, 135, 84, 0.1); width: 100%; max-width: 400px; border: 1px solid #dcfce7; }
        .brand-logo { width: 60px; height: 60px; background: #198754; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 20px; }
        .form-control:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15); }
        .btn-login { background-color: #198754; border: none; padding: 12px; font-weight: 600; font-size: 1rem; }
        .btn-login:hover { background-color: #146c43; }
    </style>
</head>
<body>

    <div class="login-card text-center">
        <div class="brand-logo"><i class="bi bi-hospital"></i></div>
        <h4 class="fw-bold mb-1">Admin Login</h4>
        <p class="text-muted mb-4 small">Silakan masuk untuk mengelola agenda</p>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small text-start">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                <label for="email">Email Address</label>
            </div>
            <div class="form-floating mb-4">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">Masuk Dashboard</button>
            <a href="{{ route('home') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left"></i> Kembali ke Jadwal</a>
        </form>
    </div>

</body>
</html>