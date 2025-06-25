<?php
session_start();
require_once '../../../sw-library/sw-config.php';
require_once '../../../sw-library/sw-function.php';

if (!isset($_SESSION['current_user'])) {
    http_response_code(403);
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

$current_user = $_SESSION['current_user'];

if ($current_user['level'] != 3) {
    http_response_code(403);
    echo json_encode(['message' => 'Access denied']);
    exit;
}

if (isset($_POST['id']) && isset($_POST['status'])) {
    $izin_id = epm_decode($_POST['id']);
    $status = $_POST['status'];

    if (!in_array($status, ['approved', 'rejected'])) {
        echo json_encode(['message' => 'Status tidak valid']);
        exit;
    }

    $supervisor_id = $current_user['user_id'];
    $now = date('Y-m-d H:i:s');

    $stmt = $connection->prepare("UPDATE izin SET supervisor_status = ?, supervisor_id = ?, supervisor_approval_at = ? WHERE izin_id = ?");
    $stmt->bind_param("sisi", $status, $supervisor_id, $now, $izin_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status berhasil diperbarui']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
}
