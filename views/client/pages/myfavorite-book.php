<?php
include '../../config/koneksi.php';

$userID = $_SESSION['user']['id'] ?? null;
$koleksiFavorit = [];

if ($userID) {
    // Ambil daftar buku favorit (koleksi pribadi) pengguna dengan jumlah ulasan
    $stmt = $pdo->prepare("
        SELECT k.KoleksiID, b.BukuID, b.Judul, b.Penulis, b.Penerbit, b.TahunTerbit,
               (SELECT COUNT(*) FROM ulasanbuku u WHERE u.BukuID = b.BukuID) AS JumlahUlasan
        FROM koleksipribadi k
        JOIN buku b ON k.BukuID = b.BukuID
        WHERE k.UserID = ?
        ORDER BY b.TahunTerbit DESC
    ");
    $stmt->execute([$userID]);
    $koleksiFavorit = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section class="p-6 space-y-6">
    <h2 class="text-2xl font-bold text-white">Buku Favoritku</h2>

    <?php if (count($koleksiFavorit) > 0): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($koleksiFavorit as $favorit): ?>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 rounded-full bg-indigo-600/20 text-indigo-300 shadow-inner">
                    <i class="fa-solid fa-book text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-white line-clamp-2">
                    <?= htmlspecialchars($favorit['Judul']) ?>
                </h3>
                <!-- Ikon bintang untuk menandakan buku favorit -->
                <div class="ml-auto">
                    <i class="fa-solid fa-star text-yellow-400"></i>
                </div>
            </div>

            <div class="text-sm text-indigo-200 space-y-2 mb-4">
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-user text-indigo-400"></i>
                    <span><span class="font-medium text-indigo-300">Penulis:</span> <?= htmlspecialchars($favorit['Penulis']) ?></span>
                </p>
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-building text-indigo-400"></i>
                    <span><span class="font-medium text-indigo-300">Penerbit:</span> <?= htmlspecialchars($favorit['Penerbit']) ?></span>
                </p>
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-day text-indigo-400"></i>
                    <span><span class="font-medium text-indigo-300">Tahun Terbit:</span> <?= htmlspecialchars($favorit['TahunTerbit']) ?></span>
                </p>
                <!-- Menampilkan jumlah ulasan -->
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-comments text-indigo-400"></i>
                    <span><span class="font-medium text-indigo-300">Jumlah Ulasan:</span> <?= $favorit['JumlahUlasan'] ?> ulasan</span>
                </p>
            </div>

            <!-- Link ke detail buku -->
            <div class="mt-auto">
                <a href="../client/index.php?page=pinjam&id=<?= $favorit['BukuID'] ?>" class="text-indigo-300 hover:text-indigo-500 transition-all duration-300">
                    <i class="fa-solid fa-arrow-right text-sm"></i> Lihat Detail Buku
                </a>
            </div>

        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p class="text-indigo-300 text-center">Kamu belum memiliki buku favorit.</p>
    <?php endif; ?>
</section>
