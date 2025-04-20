<?php
session_start();
include '../../../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    $bukuID = $_POST['bukuID'];
    $userID = $_SESSION['user']['id']; // Ambil UserID dari session

    // Validasi data
    if (empty($bukuID) || empty($userID)) {
        echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
        exit;
    }

    try {
        // Cek apakah buku ada di koleksi pribadi
        $stmt = $pdo->prepare("SELECT * FROM koleksipribadi WHERE UserID = ? AND BukuID = ?");
        $stmt->execute([$userID, $bukuID]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Buku tidak ditemukan di koleksi favorit Anda.']);
            exit;
        }

        // Hapus dari koleksi pribadi
        $stmt = $pdo->prepare("DELETE FROM koleksipribadi WHERE UserID = ? AND BukuID = ?");
        $stmt->execute([$userID, $bukuID]);

        echo json_encode(['success' => true]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus.']);
    }
}
?>
    