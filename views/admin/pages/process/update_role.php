<?php
session_start();
$role = $_SESSION['user']['role'] ?? 0;
if ($role != 3) {
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: 'Anda tidak memiliki hak akses untuk halaman ini!',
        }).then(function() {
            window.location.href = '../index.php?page=dashboard';
        });
    </script>";
    exit;
}
include '../../../../config/koneksi.php';

// Tambahkan ini biar response selalu JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'] ?? null;
    $role = $data['role'] ?? null;
    if (!$userId || !$role || !in_array($role, ['1', '2', '3'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE user SET role = ? WHERE UserID = ?");
        $stmt->execute([$role, $userId]);

        if ($stmt->rowCount() === 0) {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Pengguna tidak ditemukan']);
            exit;
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Role berhasil diperbarui']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui role: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
}
