<!-- File: tambah_izin.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Izin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <h2>Tambah Izin</h2>
    <!-- Form dari sebelumnya -->
    <form class="form-add" role="form" method="post" action="proses_tambah_izin.php" enctype="multipart/form-data" autocomplete="off">
      <div class="form-group">
        <label for="jenis">Jenis Izin</label>
        <input type="text" name="jenis" id="jenis" class="form-control" placeholder="Masukkan jenis izin" required>
      </div>

      <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
      </div>
    </form>
  </div>
</body>
</html>
