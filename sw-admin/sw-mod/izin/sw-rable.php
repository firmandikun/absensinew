<?php
session_start();
require_once '../../../sw-library/sw-config.php';
require_once '../../../sw-library/sw-function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $izin_id = isset($_POST['izin_id']) ? anti_injection($_POST['izin_id']) : null;
    $supervisor_status = isset($_POST['supervisor_status']) ? anti_injection($_POST['supervisor_status']) : null;
    $response = ["success" => false, "message" => ""];

    // Validasi hanya supervisor (level 3) yang bisa update
    if (!isset($_SESSION['level']) || $_SESSION['level'] != 3) {
        $response["message"] = "Hanya supervisor yang bisa update status.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    if ($izin_id && $supervisor_status) {
        $izin_id = intval($izin_id);
        $allowed_status = ['approved', 'rejected', 'pending'];
        if (!in_array($supervisor_status, $allowed_status)) {
            $response["message"] = "Status supervisor tidak valid.";
        } else {
            $query = "UPDATE izin SET supervisor_status='$supervisor_status' WHERE izin_id='$izin_id'";
            if ($connection->query($query) === true) {
                $response["success"] = true;
                $response["message"] = "Status supervisor berhasil diupdate.";
            } else {
                $response["message"] = "Gagal mengupdate status supervisor.";
            }
        }
    } else {
        $response["message"] = "Data tidak lengkap.";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
