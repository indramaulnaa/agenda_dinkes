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
        /* --- BACKGROUND SESUAI HALAMAN DEPAN --- */
        body { 
            font-family: 'Inter', sans-serif; 
            /* Background Gambar */
            background: url('{{ asset('images/bg_dinkes.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            position: relative; /* Penting untuk overlay */
        }

        /* Overlay Putih Transparan */
        body::before {
            content: "";
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(25, 135, 84, 0.85); /* Putih Transparan */
            backdrop-filter: blur(4px);
            z-index: -1;
        }

        .login-card { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(25, 135, 84, 0.1); width: 100%; max-width: 400px; border: 1px solid #dcfce7; }
        .brand-logo { width: 60px; height: 60px; background: #198754; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 20px; }
        
        /* Fokus Input */
        .form-control:focus { border-color: #198754; box-shadow: none; }
        /* Supaya border group terlihat rapi saat fokus */
        .input-group:focus-within { box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15); border-radius: 0.375rem; }
        .input-group:focus-within .form-control, 
        .input-group:focus-within .input-group-text { border-color: #198754; }

        .btn-login { background-color: #198754; border: none; padding: 12px; font-weight: 600; font-size: 1rem; }
        .btn-login:hover { background-color: #146c43; }

        /* Style Tombol Mata */
        .input-group-text { background-color: white; border-left: 0; cursor: pointer; color: #6c757d; }
        .input-group-text:hover { color: #198754; }
        /* Fix border input password agar menyatu dengan icon */
        .password-input { border-right: 0; }
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

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                <label for="email">Email Address</label>
            </div>

            <div class="input-group mb-4">
                <div class="form-floating flex-grow-1">
                    <input type="password" name="password" class="form-control password-input" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <span class="input-group-text" id="togglePassword">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </span>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">Masuk Dashboard</button>
            <a href="{{ url('/') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left"></i> Kembali ke Jadwal</a>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const toggleIcon = document.querySelector('#toggleIcon');

        togglePassword.addEventListener('click', function (e) {
            // 1. Ubah tipe input (password <-> text)
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // 2. Ubah ikon mata (eye <-> eye-slash)
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    </script>

</body>
</html>