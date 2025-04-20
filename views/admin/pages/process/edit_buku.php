<?php
include '../../../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $judul = $_POST['judul'] ?? '';
    $penulis = $_POST['penulis'] ?? '';
    $penerbit = $_POST['penerbit'] ?? '';
    $tahun = $_POST['tahun'] ?? '';
    $kategori = $_POST['kategori'] ?? [];

    if (!$id) {
        echo "ID buku tidak ditemukan.";
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Update data buku
        $stmt = $pdo->prepare("UPDATE buku SET Judul = ?, Penulis = ?, Penerbit = ?, TahunTerbit = ? WHERE BukuID = ?");
        $stmt->execute([$judul, $penulis, $penerbit, $tahun, $id]);

        // Hapus semua relasi kategori lama
        $deleteKategori = $pdo->prepare("DELETE FROM kategoribuku_relasi WHERE BukuID = ?");
        $deleteKategori->execute([$id]);

        // Tambahkan relasi kategori baru
        if (!empty($kategori)) {
            $insertKategori = $pdo->prepare("INSERT INTO kategoribuku_relasi (BukuID, KategoriID) VALUES (?, ?)");
            foreach ($kategori as $katID) {
                $insertKategori->execute([$id, $katID]);
            }
        }

        $pdo->commit();
        header('Location: ../../index.php?page=buku&status=updated');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Gagal mengupdate buku: " . $e->getMessage();
    }
}
?>
