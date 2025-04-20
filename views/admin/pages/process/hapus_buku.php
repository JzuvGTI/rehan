<?php
include '../../../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM buku WHERE BukuID = ?");
        if ($stmt->execute([$id_buku])) {
            echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus data.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
