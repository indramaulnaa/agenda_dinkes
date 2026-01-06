<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Agenda Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm col-md-8 mx-auto">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">📝 Tambah Agenda Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('agenda.store') }}" method="POST">
                    @csrf <div class="mb-3">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="title" class="form-control" required placeholder="Contoh: Rapat Stunting">
                    </div>

                    <div class="mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="location" class="form-control" required placeholder="Contoh: Aula Lt. 2">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Waktu Selesai</label>
                            <input type="datetime-local" name="end_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Keterangan Tambahan (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Agenda</button>
                    <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>