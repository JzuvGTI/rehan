<?php
include '../../config/koneksi.php';

if (!isset($_GET['id'])) {
    echo "Buku tidak ditemukan.";
    exit;
}

$bukuID = $_GET['id'];
$userID = $_SESSION['user']['id'] ?? null;

// Ambil data user (termasuk alamat)
$user = [];
if ($userID) {
    $stmtUser = $pdo->prepare("SELECT * FROM user WHERE UserID = ?");
    $stmtUser->execute([$userID]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
}

// Proses Tambah Ulasan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulasan'], $_POST['rating']) && $userID) {
    $ulasan = $_POST['ulasan'];
    $rating = (int) $_POST['rating'];

    $stmt = $pdo->prepare("INSERT INTO ulasanbuku (UserID, BukuID, Ulasan, Rating) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userID, $bukuID, $ulasan, $rating]);
}

// Ambil data buku
try {
    $stmt = $pdo->prepare("
        SELECT buku.*, 
        GROUP_CONCAT(kategoribuku.NamaKategori SEPARATOR ', ') AS kategori 
        FROM buku
        LEFT JOIN kategoribuku_relasi ON buku.BukuID = kategoribuku_relasi.BukuID
        LEFT JOIN kategoribuku ON kategoribuku_relasi.KategoriID = kategoribuku.KategoriID
        WHERE buku.BukuID = ?
        GROUP BY buku.BukuID
    ");
    $stmt->execute([$bukuID]);
    $buku = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$buku) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Buku Tidak Ditemukan',
                text: 'Buku yang Anda cari tidak ditemukan.',
            }).then(() => {
                window.location.href = '../client/index.php?page=pinjam-buku';
            });
        </script>";
        exit;
    }
    

    // Ambil ulasan buku
    $ulasanStmt = $pdo->prepare("
        SELECT ub.*, u.NamaLengkap 
        FROM ulasanbuku ub
        JOIN user u ON ub.UserID = u.UserID
        WHERE ub.BukuID = ?
        ORDER BY ub.UlasanID DESC
    ");
    $ulasanStmt->execute([$bukuID]);
    $ulasanbuku = $ulasanStmt->fetchAll(PDO::FETCH_ASSOC);

    // Proses Pinjam Buku
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pinjam_buku']) && $userID) {
        try {
            // Cek apakah buku sudah dipinjam oleh user
            $cekPinjamStmt = $pdo->prepare("SELECT * FROM peminjaman WHERE UserID = ? AND BukuID = ? AND StatusPeminjaman = 'Dipinjam'");
            $cekPinjamStmt->execute([$userID, $bukuID]);
            $bukuSudahDipinjam = $cekPinjamStmt->fetch(PDO::FETCH_ASSOC);

            if ($bukuSudahDipinjam) {
                // Jika buku sudah dipinjam, tampilkan pesan
                echo "<script>
                    Swal.fire('Buku Sudah Dipinjam', 'Anda sudah meminjam buku ini.', 'warning');
                </script>";
            } else {
                // Lanjutkan proses peminjaman jika buku belum dipinjam
                if (!empty($user['Alamat'])) {
                    $tanggalPinjam = date('Y-m-d');
                    $stmt = $pdo->prepare("INSERT INTO peminjaman (UserID, BukuID, TanggalPeminjaman, StatusPeminjaman) VALUES (?, ?, ?, 'Dipinjam')");
                    $stmt->execute([$userID, $bukuID, $tanggalPinjam]);

                    echo "<script>
                        Swal.fire('Peminjaman Berhasil!', 'Buku berhasil dipinjam.', 'success');
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire('Alamat Tidak Tersedia', 'Silakan perbarui alamat Anda terlebih dahulu.', 'error');
                    </script>";
                }
            }
        } catch (PDOException $e) {
            echo "Gagal memproses peminjaman: " . $e->getMessage();
        }
    }

} catch (PDOException $e) {
    echo "Gagal mengambil data: " . $e->getMessage();
    exit;
}
?>


<section class="grid md:grid-cols-2 gap-6 p-6">
    <!-- Info Buku -->
    <div class="bg-indigo-900/10 backdrop-blur-md border border-indigo-500/20 shadow-xl rounded-2xl p-6 text-indigo-100 self-start">
        <div class="flex items-center gap-4 mb-4">
            <i class="fa-solid fa-book text-indigo-300 text-3xl"></i>
            <h2 class="text-2xl font-extrabold text-sm sm:text-xl"><?= htmlspecialchars($buku['Judul']) ?></h2>
        </div>

        <ul class="space-y-2 text-sm">
            <li class="flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-indigo-400"></i>
                <span><strong>Penulis:</strong> <?= htmlspecialchars($buku['Penulis']) ?></span>
            </li>
            <li class="flex items-center gap-2">
                <i class="fa-solid fa-building text-indigo-400"></i>
                <span><strong>Penerbit:</strong> <?= htmlspecialchars($buku['Penerbit']) ?></span>
            </li>
            <li class="flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-indigo-400"></i>
                <span><strong>Tahun:</strong> <?= htmlspecialchars($buku['TahunTerbit']) ?></span>
            </li>
            <li class="flex items-center gap-2">
                <i class="fa-solid fa-tags text-indigo-400"></i>
                <span><strong>Kategori:</strong> 
                    <?php foreach (explode(',', $buku['kategori']) as $kategori): ?>
                        <span class="inline-block bg-indigo-700/40 text-indigo-100 px-2 py-0.5 rounded-full text-xs mr-1 mb-1">
                            <?= trim($kategori) ?>
                        </span>
                    <?php endforeach; ?>
                </span>
            </li>
            <li class="flex items-center gap-2">
                <i class="fa-solid fa-location-dot text-indigo-400"></i>
                <span><strong>Alamatmu:</strong> <?= htmlspecialchars($user['Alamat']) ?></span>
            </li>
        </ul>


        <button id="pinjamBukuBtn" class="mt-4 inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-5 py-2 rounded-xl transition-all duration-300">
            <i class="fa-solid fa-book-open-reader"></i>
            Pinjam Buku
        </button>
    </div>

    <!-- Ulasan dan Form -->
    <div class="bg-indigo-900/10 backdrop-blur-md border border-indigo-500/20 shadow-xl rounded-2xl p-6 text-indigo-100">
        <h3 class="text-lg font-bold mb-4 border-b border-indigo-400/30 pb-2">Ulasan Pembaca</h3>
        
        <!-- List Ulasan -->
        <?php if (count($ulasanbuku) > 0): ?>
            <p class="text-sm text-indigo-300 mb-2">
                Total: <?= count($ulasanbuku) ?> Ulasan
            </p>
        <?php else: ?>
            <p class="text-sm text-red-300 italic mb-4">
                Belum ada ulasan untuk buku ini.
            </p>
        <?php endif; ?>

        <div class="space-y-4 max-h-40 overflow-y-auto pr-2 mb-6">
            <?php foreach ($ulasanbuku as $ulasan): ?>
                <div class="bg-indigo-800/30 border border-indigo-500/20 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-semibold"><?= htmlspecialchars($ulasan['NamaLengkap']) ?></span>
                    <span class="text-yellow-400">
                        <?php for ($i = 0; $i < $ulasan['Rating']; $i++): ?>
                            <i class="fa-solid fa-star text-xs sm:text-sm md:text-base lg:text-lg"></i>
                        <?php endfor; ?>
                    </span>

                    </div>
                    <p class="text-sm text-indigo-200"><?= htmlspecialchars($ulasan['Ulasan']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Form Tambah Ulasan -->
        <form method="POST" class="space-y-4">
            <textarea name="ulasan" required rows="3" placeholder="Tulis ulasanmu..." class="w-full p-3 rounded-lg bg-indigo-800/20 border border-indigo-500/20 text-white placeholder-indigo-300"></textarea>
            <div class="flex items-center gap-4">
                <label class="text-sm">Rating:</label>
                <select name="rating" class="bg-indigo-800/20 border border-indigo-500/20 text-white rounded-md px-3 py-2">
                    <option value="5">★★★★★ (5)</option>
                    <option value="4">★★★★☆ (4)</option>
                    <option value="3">★★★☆☆ (3)</option>
                    <option value="2">★★☆☆☆ (2)</option>
                    <option value="1">★☆☆☆☆ (1)</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 px-5 py-2 rounded-xl text-white font-semibold">
                <i class="fa-solid fa-paper-plane"></i> Kirim Ulasan
            </button>
        </form>
    </div>
</section>
<script>
    // Menggunakan SweetAlert untuk konfirmasi peminjaman
    document.getElementById('pinjamBukuBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Apakah Anda yakin ingin meminjam buku ini?',
            text: "Klik 'Ya' untuk melanjutkan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim form peminjaman
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'pinjam_buku';
                input.value = '1';
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>