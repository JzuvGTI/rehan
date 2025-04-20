<?php
include '../../../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['peminjaman_id'];

    try {
        $stmt = $pdo->prepare("UPDATE peminjaman SET TanggalPengembalian = NOW(), StatusPeminjaman = 'Dikembalikan' WHERE PeminjamanID = ?");
        $stmt->execute([$id]);
        echo "success";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "error";
    }
}
