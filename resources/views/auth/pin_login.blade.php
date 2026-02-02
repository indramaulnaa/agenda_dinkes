<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukan PIN - Dinas Kesehatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=2053&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(25, 135, 84, 0.85); /* Hijau Dinkes Transparan */
            backdrop-filter: blur(8px); z-index: -1;
        }
        .pin-card {
            background: white; padding: 40px; border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); text-align: center;
            width: 100%; max-width: 400px;
        }
        .pin-input {
            font-size: 2rem; letter-spacing: 10px; text-align: center;
            border: 2px solid #e9ecef; border-radius: 10px; padding: 10px;
            font-weight: 800; color: #198754;
        }
        .pin-input:focus { border-color: #198754; box-shadow: none; outline: none; }
        .btn-enter {
            background: #198754; color: white; border: none; padding: 12px;
            width: 100%; border-radius: 10px; font-weight: 700; margin-top: 20px;
            transition: 0.3s;
        }
        .btn-enter:hover { background: #146c43; transform: scale(1.02); }

        /* --- TAMBAHAN STYLE UNTUK FOOTER LINK --- */
        .footer-link {
            font-size: 0.7rem; 
            color: #adb5bd; /* Warna abu-abu halus */
            text-decoration: none; 
            display: block; 
            margin-top: 15px;
            transition: 0.3s;
        }
        .footer-link:hover { 
            color: #198754; /* Berubah hijau saat di-hover */
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="pin-card">
        <div class="mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" width="60" class="mb-3">
            <h4 class="fw-bold text-dark">Agenda Dinas</h4>
            <p class="text-secondary small">Masukkan PIN Akses Pegawai</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif

        <form action="{{ route('pin.verify') }}" method="POST">
            @csrf
            <input type="password" name="pin" class="form-control pin-input" maxlength="6" placeholder="******" autofocus required>
            <button type="submit" class="btn btn-enter">MASUK <i class="bi bi-arrow-right"></i></button>
        </form>
        
        <div class="mt-4 pt-3 border-top">
            <a href="{{ route('login') }}" class="text-decoration-none small text-secondary fw-bold">Login sebagai Admin</a>

            <a href="https://www.linkedin.com/in/indramaulanahasan" target="_blank" class="footer-link">
                &copy; 2026 Dinas Kesehatan - Magang UNNES
            </a>
        </div>
    </div>

</body>
</html>