<?php
include '../../../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $kategori = $_POST['kategori'] ?? [];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO buku (Judul, Penulis, Penerbit, TahunTerbit) VALUES (?, ?, ?, ?)");
        $stmt->execute([$judul, $penulis, $penerbit, $tahun]);

        $lastBukuID = $pdo->lastInsertId();

        // Insert ke relasi kategori
        if (!empty($kategori)) {
            $relasiStmt = $pdo->prepare("INSERT INTO kategoribuku_relasi (BukuID, KategoriID) VALUES (?, ?)");
            foreach ($kategori as $katID) {
                $relasiStmt->execute([$lastBukuID, $katID]);
            }
        }

        $pdo->commit();
        header('Location: ../../index.php?page=buku');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Gagal menambahkan buku: " . $e->getMessage();
    }
}
?>
