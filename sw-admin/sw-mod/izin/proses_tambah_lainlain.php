<?php
// proses_tambah_lainlain.php
include 'koneksi.php';

$nama = $_POST['nama'] ?? '';
$tipe = $_POST['tipe'] ?? '';

if ($nama && $tipe) {
    $query = "INSERT INTO lain_lain (nama, tipe) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nama, $tipe);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal simpan data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
}
?>
