<?php
include '../../config/koneksi.php'; 

try {
    $stmt = $pdo->query("
        SELECT buku.BukuID, buku.Judul, buku.Penulis, buku.Penerbit, buku.TahunTerbit,
               GROUP_CONCAT(kategoribuku.NamaKategori) AS Kategori
        FROM buku
        LEFT JOIN kategoribuku_relasi ON buku.BukuID = kategoribuku_relasi.BukuID
        LEFT JOIN kategoribuku ON kategoribuku_relasi.KategoriID = kategoribuku.KategoriID
        GROUP BY buku.BukuID
    ");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $kategoriStmt = $pdo->query("SELECT * FROM kategoribuku");
    $allKategori = $kategoriStmt->fetchAll(PDO::FETCH_ASSOC);


    // Ambil Buku favorite  
    $userID = $_SESSION['user']['id'] ?? null;
    $favoritIDs = [];

    if ($userID) {
        $favStmt = $pdo->prepare("SELECT BukuID FROM koleksipribadi WHERE UserID = ?");
        $favStmt->execute([$userID]);
        $favoritIDs = array_column($favStmt->fetchAll(PDO::FETCH_ASSOC), 'BukuID');
    }
    // Ambil daftar buku yang sedang dipinjam user
    $peminjamanIDs = [];

    if ($userID) {
        $pinjamStmt = $pdo->prepare("SELECT BukuID FROM peminjaman WHERE UserID = ? AND StatusPeminjaman = 'dipinjam'");
        $pinjamStmt->execute([$userID]);
        $peminjamanIDs = array_column($pinjamStmt->fetchAll(PDO::FETCH_ASSOC), 'BukuID');
    }

} catch (PDOException $e) {
    echo "Gagal ambil data buku: " . $e->getMessage();
    $books = [];
}
?>
<section class="p-6 space-y-6">
    <!-- Filter Input -->
    <div class="grid sm:grid-cols-2 gap-4">
        <input 
            type="text" 
            id="searchInput" 
            placeholder="Cari judul atau penulis..." 
            class="w-full px-4 py-2 bg-white/5 text-white border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:text-indigo-300 transition-all"
        >

        <select id="kategoriFilter" class="w-full px-4 py-2 bg-white/5 text-gray-300 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option class="bg-gray-600" value="">Semua Kategori</option>
            <?php foreach ($allKategori as $kategori): ?>
                <option class="bg-gray-600" value="<?= strtolower($kategori['NamaKategori']) ?>">
                    <?= htmlspecialchars($kategori['NamaKategori']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Buku Cards -->
    <div id="bookContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($books as $buku): ?>
            <div 
                class="book-card relative bg-white/5 border border-white/10 rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 group flex flex-col"
                data-title="<?= strtolower($buku['Judul']) ?>" 
                data-author="<?= strtolower($buku['Penulis']) ?>" 
                data-kategori="<?= strtolower($buku['Kategori']) ?>">

                <!-- Favorite Button -->
                <?php $isFavorit = in_array($buku['BukuID'], $favoritIDs); ?>
                <button 
                    class="absolute top-3 right-3 transition-all z-10 <?= $isFavorit ? 'text-yellow-400' : 'text-indigo-300 hover:text-yellow-400' ?>"
                    data-buku-id="<?= $buku['BukuID'] ?>"
                    data-favorit="<?= $isFavorit ? '1' : '0' ?>"
                    onclick="toggleFavorite(this)">
                    <i class="<?= $isFavorit ? 'fa-solid' : 'fa-regular' ?> fa-star text-xl"></i>
                </button>



                <!-- Isi Konten -->
                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 rounded-full bg-indigo-600/20 text-indigo-300 shadow-inner">
                            <i class="fa-solid fa-book text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-white line-clamp-2"><?= htmlspecialchars($buku['Judul']) ?></h3>
                    </div>

                    <div class="text-sm text-indigo-200 space-y-2 mb-6">
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-user text-indigo-400"></i>
                            <span><span class="font-medium text-indigo-300">Penulis:</span> <?= htmlspecialchars($buku['Penulis']) ?></span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-building text-indigo-400"></i>
                            <span><span class="font-medium text-indigo-300">Penerbit:</span> <?= htmlspecialchars($buku['Penerbit']) ?></span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar text-indigo-400"></i>
                            <span><span class="font-medium text-indigo-300">Tahun:</span> <?= htmlspecialchars($buku['TahunTerbit']) ?></span>
                        </p>
                        <p class="flex flex-col items-start gap-2">
                            <span>
                                <i class="fa-solid fa-tags text-indigo-400 mt-0.5"></i>
                                <span class="font-medium text-indigo-300">Kategori:</span> 
                                <?php 
                                if (!empty($buku['Kategori'])): 
                                    // Pisahkan kategori dengan koma, kemudian buat badge untuk setiap kategori
                                    $kategoris = explode(',', $buku['Kategori']);
                                    foreach ($kategoris as $kategori):
                                ?>
                                        <span class="inline-block bg-indigo-700/40 text-indigo-100 px-3 py-1 rounded-full text-xs mb-2">
                                            <?= trim($kategori) ?>
                                        </span>
                                <?php 
                                    endforeach;
                                else: 
                                ?>
                                    <span class="text-red-400">-</span>
                                <?php endif; ?>
                            </span>
                        </p>

                    </div>
                </div>

                <div class="mt-auto pt-4">
                    <?php if (in_array($buku['BukuID'], $peminjamanIDs)): ?>
                        <!-- Tombol jika buku sudah dipinjam -->
                        <a href="#" 
                        class="w-full flex items-center justify-center gap-2 px-5 py-2 rounded-xl bg-gray-400 text-white font-semibold cursor-not-allowed">
                            <i class="fa-solid fa-book-open-reader"></i>
                            Sudah Kamu Pinjam
                        </a>
                    <?php else: ?>
                        <!-- Tombol untuk meminjam buku -->
                        <a href="index.php?page=pinjam&id=<?= $buku['BukuID'] ?>" 
                        class="w-full flex items-center justify-center gap-2 px-5 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition-all duration-300">
                            <i class="fa-solid fa-book-open-reader"></i>
                            Pinjam Buku
                        </a>
                    <?php endif; ?>
                </div>



            </div>
        <?php endforeach; ?>
    </div>

</section>
<script>
function toggleFavorite(button) {
    const bukuID = button.getAttribute('data-buku-id');
    const isFavorit = button.getAttribute('data-favorit') === '1';
    const userID = <?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null'; ?>;

    if (userID === null) {
        Swal.fire('Error', 'Anda harus login terlebih dahulu untuk mengelola favorit.', 'error');
        return;
    }

    // SweetAlert konfirmasi
    Swal.fire({
        title: isFavorit ? 'Hapus dari Koleksi Favorit?' : 'Tambahkan ke Koleksi Favorit?',
        text: isFavorit 
            ? "Buku ini akan dihapus dari koleksi favorit Anda." 
            : "Apakah Anda yakin ingin menambah buku ini ke koleksi pribadi?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isFavorit ? '#d33' : '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: isFavorit ? 'Ya, Hapus!' : 'Ya, Tambahkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(isFavorit ? 'pages/process/remove_favorite.php' : 'pages/process/add_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'bukuID': bukuID,
                    'userID': userID
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (isFavorit) {
                        // Ganti jadi tidak favorit
                        button.classList.remove('text-yellow-400');
                        button.classList.add('text-indigo-300', 'hover:text-yellow-400');
                        button.querySelector('i').classList.remove('fa-solid');
                        button.querySelector('i').classList.add('fa-regular');
                        button.setAttribute('data-favorit', '0');
                        Swal.fire('Dihapus!', 'Buku telah dihapus dari koleksi favorit Anda.', 'success');
                    } else {
                        // Ganti jadi favorit
                        button.classList.remove('text-indigo-300', 'hover:text-yellow-400');
                        button.classList.add('text-yellow-400');
                        button.querySelector('i').classList.remove('fa-regular');
                        button.querySelector('i').classList.add('fa-solid');
                        button.setAttribute('data-favorit', '1');
                        Swal.fire('Ditambahkan!', 'Buku telah ditambahkan ke koleksi favorit.', 'success');
                    }
                } else {
                    Swal.fire('Oops!', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Terjadi kesalahan, coba lagi nanti.', 'error');
            });
        }
    });
}
</script>


<script>
    const searchInput = document.getElementById('searchInput');
    const kategoriFilter = document.getElementById('kategoriFilter');
    const cards = document.querySelectorAll('.book-card');

    function filterBooks() {
        const keyword = searchInput.value.toLowerCase();
        const selectedKategori = kategoriFilter.value;

        cards.forEach(card => {
            const title = card.dataset.title;
            const author = card.dataset.author;
            const kategori = card.dataset.kategori;

            const matchText = title.includes(keyword) || author.includes(keyword);
            const matchKategori = selectedKategori === "" || kategori.includes(selectedKategori);

            card.style.display = (matchText && matchKategori) ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterBooks);
    kategoriFilter.addEventListener('change', filterBooks);
</script>
