<?php
include '../../config/koneksi.php';

$userID = $_SESSION['user']['id'] ?? null;
$peminjaman = [];

if ($userID) {
    $stmt = $pdo->prepare("
        SELECT p.PeminjamanID, p.TanggalPeminjaman, p.TanggalPengembalian, p.StatusPeminjaman, 
               b.Judul, b.Penulis
        FROM peminjaman p
        JOIN buku b ON p.BukuID = b.BukuID
        WHERE p.UserID = ?
        ORDER BY p.TanggalPengembalian DESC
    ");
    $stmt->execute([$userID]);
    $peminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section class="p-6 space-y-6">
    <h2 class="text-2xl font-bold text-white">Peminjamanku</h2>

    <?php if (count($peminjaman) > 0): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($peminjaman as $pinjam): ?>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 rounded-full bg-indigo-600/20 text-indigo-300 shadow-inner">
                    <i class="fa-solid fa-book text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-white line-clamp-2">
                    <?= htmlspecialchars($pinjam['Judul']) ?>
                </h3>
            </div>

            <div class="text-sm text-indigo-200 space-y-2 mb-4">
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-user text-indigo-400"></i>
                    <span><span class="font-medium text-indigo-300">Penulis:</span> <?= htmlspecialchars($pinjam['Penulis']) ?></span>
                </p>
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-day text-indigo-400"></i>
                    <span><span class="font-medium text-indigo-300">Tanggal Pinjam:</span> <?= date('d M Y', strtotime($pinjam['TanggalPeminjaman'])) ?></span>
                </p>
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-indigo-400"></i>
                    <span><span class="font-medium text-indigo-300">Harus Kembali:</span> <?= date('d M Y', strtotime($pinjam['TanggalPengembalian'])) ?></span>
                </p>
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-indigo-400"></i>
                    <span>
                        <span class="font-medium text-indigo-300">Status:</span> 
                        <span class="<?= $pinjam['StatusPeminjaman'] === 'Dipinjam' ? 'text-yellow-400' : 'text-green-400' ?>">
                            <?= $pinjam['StatusPeminjaman'] ?>
                        </span>
                    </span>
                </p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p class="text-indigo-300 text-center">Kamu belum meminjam buku apapun.</p>
    <?php endif; ?>
</section>
