<?php
session_start();
require_once '../../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email dan Password wajib diisi!';
        header('Location: ../login.php');
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['Password'])) {
            $_SESSION['error'] = 'Email atau password salah!';
            header('Location: ../login.php');
            exit;
        }

        $_SESSION['user'] = [
            'id'       => $user['UserID'],
            'name'     => $user['NamaLengkap'],
            'email'    => $user['Email'],
            'role'     => $user['role']
        ];

        $_SESSION['success'] = 'Berhasil login!';

        switch ($user['role']) {
            case 1:
                header('Location: ../../client/index.php?page=pinjam-buku');
                break;
            case 2:
                header('Location: ../../admin/index.php?page=dashboard');
                break;
            case 3:
                header('Location: ../../admin/index.php?page=dashboard');
                break;
            default:
                $_SESSION['error'] = 'Maaf terjadi kesalahan ketika login. Harap hubungi Administrator';
                header('Location: ../login.php');
                break;
        }
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Maaf terjadi kesalahan ketika login. Harap hubungi Administrator';
        header('Location: ../login.php');
        exit;
    }
} else {
    header('Location: ../login.php');
    exit;
}
