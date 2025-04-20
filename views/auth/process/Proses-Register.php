<?php
session_start();
require_once '../../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaLengkap = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $username    = explode('@', $email)[0];
    $password    = $_POST['password'] ?? '';
    $confirm     = $_POST['confirm'] ?? '';
    $alamat      = trim($_POST['alamat'] ?? ''); // Menambahkan alamat

    if (empty($namaLengkap) || empty($email) || empty($password) || empty($confirm) || empty($alamat)) {
        $_SESSION['error'] = 'Semua field wajib diisi!';
        header('Location: ../register.php');
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['error'] = 'Password dan konfirmasi tidak cocok!';
        header('Location: ../register.php');
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE Email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Email sudah terdaftar!';
            header('Location: ../register.php');
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $hashed, $email, $namaLengkap, $alamat, 1]); // Memasukkan alamat ke database

        $_SESSION['success'] = 'Akun berhasil dibuat. Silakan login!';
        header('Location: ../login.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Gagal register: ' . $e->getMessage();
        header('Location: ../register.php');
        exit;
    }
} else {
    header('Location: ../register.php');
    exit;
}
?>
