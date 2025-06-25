<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])) {
    header('Location: ./login');
    exit;
}

require_once dirname(__DIR__, 3) . '/sw-library/sw-config.php';
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Ambil action dari POST, jika ada
$action = $_POST['action'] ?? '';

// Debug POST data untuk update
if ($action === 'update') {
    echo "<pre>POST data:\n";
    var_dump($_POST);
    echo "</pre>";
    // exit;  // uncomment ini jika mau berhenti untuk debugging dulu
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $nama = $connection->real_escape_string($_POST['nama'] ?? '');
        $tipe = $connection->real_escape_string($_POST['tipe'] ?? '');
        $jumlah = $connection->real_escape_string($_POST['jumlah'] ?? '0');

        if ($nama === '' || $tipe === '') {
            $error = "Nama dan Tipe harus diisi";
        } else {
            $stmt = $connection->prepare("INSERT INTO lain_lain (nama, tipe, jumlah) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $tipe, $jumlah);
            $stmt->execute();
            $stmt->close();
            header("Location: ./type-izin");
            exit;
        }
    } elseif ($action === 'update') {
        $id = intval($_POST['id'] ?? 0);
        $nama = $connection->real_escape_string($_POST['nama'] ?? '');
        $tipe = $connection->real_escape_string($_POST['tipe'] ?? '');
        $jumlah = $connection->real_escape_string($_POST['jumlah'] ?? '0');

        if ($id > 0 && $nama !== '' && $tipe !== '') {
            $stmt = $connection->prepare("UPDATE lain_lain SET nama = ?, tipe = ?, jumlah = ? WHERE lain_lain_id = ?");
            $stmt->bind_param("ssii", $nama, $tipe, $jumlah, $id);
            $stmt->execute();
            $stmt->close();
            header("Location: ./type-izin");
            exit;
        } else {
            $error = "Data tidak valid untuk update";
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $connection->prepare("DELETE FROM lain_lain WHERE lain_lain_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            header("Location: ./type-izin");
            exit;
        } else {
            $error = "ID tidak valid untuk hapus";
        }
    }
}

$result = $connection->query("SELECT * FROM lain_lain WHERE tipe != 'timezone' ORDER BY lain_lain_id DESC");


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Type Izin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div class="container-fluid mt-5">
    <h2>Daftar Type Izin</h2>

    <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAdd">
        Tambah Type Izin
    </button>

   <table class="table align-items-center table-striped datatable">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['tipe']) ?></td>
                <td><?= htmlspecialchars($row['jumlah']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalEdit<?= $row['lain_lain_id'] ?>">
                        Edit
                    </button>

                    <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?');">
                        <input type="hidden" name="action" value="delete" />
                        <input type="hidden" name="id" value="<?= $row['lain_lain_id'] ?>" />
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            
            <!-- Modal Edit untuk setiap row -->
            <div class="modal fade" id="modalEdit<?= $row['lain_lain_id'] ?>" tabindex="-1" aria-labelledby="modalEditLabel<?= $row['lain_lain_id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditLabel<?= $row['lain_lain_id'] ?>">Edit Type Izin</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="editNama<?= $row['lain_lain_id'] ?>" class="form-label">Nama</label>
                                    <input type="text" 
                                           name="nama" 
                                           id="editNama<?= $row['lain_lain_id'] ?>"
                                           class="form-control" 
                                           value="<?= htmlspecialchars($row['nama']) ?>" 
                                           required 
                                           autocomplete="off" />
                                </div>
                                <div class="mb-3">
                                    <label for="editTipe<?= $row['lain_lain_id'] ?>" class="form-label">Tipe</label>
                                    <input type="text" 
                                           name="tipe" 
                                           id="editTipe<?= $row['lain_lain_id'] ?>"
                                           class="form-control" 
                                           value="<?= htmlspecialchars($row['tipe']) ?>" 
                                           required 
                                           autocomplete="off" />
                                </div>
                                <div class="mb-3">
                                    <label for="editJumlah<?= $row['lain_lain_id'] ?>" class="form-label">Jumlah Hari</label>
                                    <input type="number" 
                                           name="jumlah" 
                                           id="editJumlah<?= $row['lain_lain_id'] ?>"
                                           class="form-control" 
                                           value="<?= htmlspecialchars($row['jumlah']) ?>" 
                                           required />
                                </div>
                                <input type="hidden" name="id" value="<?= $row['lain_lain_id'] ?>" />
                                <input type="hidden" name="action" value="update" />
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Add -->
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content" id="formAdd">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Type Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required />
                <input type="text" name="tipe" class="form-control mb-2" placeholder="Tipe" required />
                <input type="number" name="jumlah" class="form-control" placeholder="Jumlah Hari" required />
                <input type="hidden" name="action" value="add" />
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Load Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Simple script to ensure modals work properly
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus pada input pertama ketika modal dibuka
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const firstInput = this.querySelector('input[type="text"]:not([readonly])');
            if (firstInput) {
                firstInput.focus();
                firstInput.select(); // Select all text for easy editing
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    // Handle form submissions
    $('#formAdd').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(response) {
                $('#modalAdd').modal('hide');
                swal({
                    title: "Berhasil!",
                    text: "Data berhasil ditambahkan!",
                    icon: "success",
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            }
        });
    });

    // Handle edit form submissions
    $('.form-edit').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(response) {
                $('.modal').modal('hide');
                swal({
                    title: "Berhasil!",
                    text: "Data berhasil diupdate!",
                    icon: "success",
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            }
        });
    });

    // Handle delete
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        swal({
            title: "Anda yakin?",
            text: "Data akan dihapus permanen!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
                        swal({
                            title: "Berhasil!",
                            text: "Data berhasil dihapus!",
                            icon: "success",
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>

<?php
$connection->close();
?>