<?php
session_start();
include '../../../../config/koneksi.php';

// Cek jika pengguna belum login
if (!isset($_SESSION['user']['id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

$userID = $_SESSION['user']['id'];
$alamat = $_POST['alamat'] ?? null;
$bukuID = $_GET['id'] ?? null; // Ambil ID buku dari parameter URL

// Validasi input alamat
if (empty($alamat)) {
    echo "Alamat tidak boleh kosong.";
    exit;
}

// Validasi ID buku
if (empty($bukuID)) {
    echo "ID buku tidak ditemukan.";
    exit;
}

try {
    // Cek apakah user sudah memiliki alamat
    $stmt = $pdo->prepare("SELECT alamat FROM user WHERE UserID = ?");
    $stmt->execute([$userID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !empty($user['alamat'])) {
        // Jika user sudah punya alamat
        echo "Anda sudah memiliki alamat yang terdaftar.";
        exit;
    }

    // Simpan alamat baru
    $stmt = $pdo->prepare("UPDATE user SET alamat = ? WHERE UserID = ?");
    $stmt->execute([$alamat, $userID]);

    // Redirect ke halaman pinjam buku, dengan menyertakan ID buku
    header("Location: ../../index.php?page=pinjam&id=" . $bukuID); // Ganti dengan halaman yang sesuai
    exit;
} catch (PDOException $e) {
    echo "Gagal menambahkan alamat: " . $e->getMessage();
    exit;
}
?>
