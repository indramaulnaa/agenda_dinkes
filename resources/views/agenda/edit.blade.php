<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm col-md-8 mx-auto">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">✏️ Edit Agenda</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('agenda.update', $agenda->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="mb-3">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="title" class="form-control" 
                               value="{{ $agenda->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="location" class="form-control" 
                               value="{{ $agenda->location }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" class="form-control" 
                                   value="{{ $agenda->start_time }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Waktu Selesai</label>
                            <input type="datetime-local" name="end_time" class="form-control" 
                                   value="{{ $agenda->end_time }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Keterangan Tambahan</label>
                        <textarea name="description" class="form-control" rows="3">{{ $agenda->description }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui Data</button>
                    <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>