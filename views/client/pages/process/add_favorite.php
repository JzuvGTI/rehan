<?php
session_start();
include '../../../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    $bukuID = $_POST['bukuID'];
    $userID = $_SESSION['user']['id']; // Ambil UserID dari session yang sudah login

    // Pastikan data valid
    if (empty($bukuID) || empty($userID)) {
        echo json_encode(['success' => false]);
        exit;
    }

    try {
        // Cek apakah buku sudah ada di koleksi pribadi
        $stmt = $pdo->prepare("SELECT * FROM koleksipribadi WHERE UserID = ? AND BukuID = ?");
        $stmt->execute([$userID, $bukuID]);

        if ($stmt->rowCount() > 0) {
            // Buku sudah ada di koleksi pribadi
            echo json_encode(['success' => false, 'message' => 'Buku sudah ada di koleksi favorit Anda.']);
            exit;
        }

        // Menambahkan buku ke koleksi pribadi
        $stmt = $pdo->prepare("INSERT INTO koleksipribadi (UserID, BukuID) VALUES (?, ?)");
        $stmt->execute([$userID, $bukuID]);

        echo json_encode(['success' => true]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan.']);
    }
}

?>