<?php
include '../../config/koneksi.php'; 

try {
    $stmt = $pdo->query("
        SELECT buku.BukuID, buku.Judul, buku.Penulis, buku.Penerbit, buku.TahunTerbit, GROUP_CONCAT(kategoribuku.NamaKategori) AS Kategori
        FROM buku
        LEFT JOIN kategoribuku_relasi ON buku.BukuID = kategoribuku_relasi.BukuID
        LEFT JOIN kategoribuku ON kategoribuku_relasi.KategoriID = kategoribuku.KategoriID
        GROUP BY buku.BukuID
    ");
    $kategoriStmt = $pdo->query("SELECT * FROM kategoribuku");
    $kategori = $kategoriStmt->fetchAll(PDO::FETCH_ASSOC);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Gagal mengambil data buku: " . $e->getMessage();
    $books = [];
}
?>

<section class="space-y-6">
    <button onclick="toggleModal('modalTambahBuku')" class="bg-indigo-700 hover:bg-indigo-600 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-300">
        <i class="fa-solid fa-plus"></i> Tambah Buku
    </button>

    <div data-aos="fade-up" class="overflow-x-auto rounded-xl">
        <table id="tabel-buku" class="min-w-full text-sm text-indigo-100 border border-indigo-500/20">
            <thead class="bg-indigo-800 text-indigo-100">
                <tr>
                    <th></th>
                    <th class="px-4 py-3 text-left">Judul</th>
                    <th class="px-4 py-3 text-left">Penulis</th>
                    <th class="px-4 py-3 text-left">Penerbit</th>
                    <th class="px-4 py-3 text-left">Tahun Terbit</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $buku) : ?>
                    <tr class="border-t border-indigo-500/10 hover:bg-indigo-800/30 transition duration-300">
                        <td></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($buku['Judul']); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($buku['Penulis']); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($buku['Penerbit']); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($buku['TahunTerbit']); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($buku['Kategori']); ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <!-- Edit Button -->
                                <button onclick='openEditModal({
                                    id: <?= $buku["BukuID"] ?>,
                                    judul: <?= json_encode($buku["Judul"]) ?>,
                                    penulis: <?= json_encode($buku["Penulis"]) ?>,
                                    penerbit: <?= json_encode($buku["Penerbit"]) ?>,
                                    tahun: <?= json_encode($buku["TahunTerbit"]) ?>,
                                    kategori: <?= json_encode($buku["Kategori"]) ?>
                                })'
                                class="text-xs font-medium px-2.5 py-1 border border-yellow-500 text-yellow-400 bg-white/5 hover:bg-yellow-500/20 hover:border-yellow-600 hover:text-yellow-300 rounded-md backdrop-blur-sm shadow-sm transition-all duration-300 flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                    Edit
                                </button>


                                <!-- Delete Button -->
                                <button onclick="confirmDelete(<?= $buku['BukuID'] ?>)" 
                                    class="text-xs font-medium px-2.5 py-1 border border-red-500 text-red-400 bg-white/5 hover:bg-red-600/20 hover:border-red-600 hover:text-red-300 rounded-md backdrop-blur-sm shadow-sm transition-all duration-300 flex items-center gap-1">
                                    <i class="fa-solid fa-trash text-[11px]"></i>
                                    Delete
                                </button>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal Edit -->
<div id="modalEditBuku" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 overflow-y-hidden">
    <div class="bg-indigo-900/50 border border-indigo-500/70 rounded-2xl w-full max-w-lg mx-4 p-6 shadow-lg space-y-6 relative max-h-[80vh] overflow-y-auto">
        <h2 class="text-xl font-semibold text-indigo-300">Edit Buku</h2>
        <form id="formEditBuku" action="pages/process/edit_buku.php" method="POST" class="space-y-4">
            <input type="hidden" name="id" id="edit-id">
            <div>
                <label class="block font-semibold text-indigo-200">Judul Buku</label>
                <input type="text" name="judul" id="edit-judul" class="w-full px-4 py-2 rounded-lg border text-indigo-900 bg-gray-400" required>
            </div>
            <div>
                <label class="block font-semibold text-indigo-200">Penulis</label>
                <input type="text" name="penulis" id="edit-penulis" class="w-full px-4 py-2 rounded-lg border text-indigo-900 bg-gray-400" required>
            </div>
            <div>
                <label class="block font-semibold text-indigo-200">Penerbit</label>
                <input type="text" name="penerbit" id="edit-penerbit" class="w-full px-4 py-2 rounded-lg border text-indigo-900 bg-gray-400">
            </div>
            <div>
                <label class="block font-semibold text-indigo-200">Tahun Terbit</label>
                <input type="number" name="tahun" id="edit-tahun" class="w-full px-4 py-2 rounded-lg border text-indigo-900 bg-gray-400">
            </div>
            <div>
                <label class="block font-semibold text-indigo-200">Kategori</label>
                <select name="kategori[]" id="edit-kategori" multiple class="w-full px-4 py-2 rounded-lg border text-indigo-900 bg-gray-400">
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= $kat['KategoriID'] ?>" 
                            <?php 
                                // Mengecek apakah kategori ini sudah terpilih
                                if (in_array($kat['NamaKategori'], explode(',', $buku['Kategori']))) {
                                    echo 'selected'; 
                                }
                            ?>>
                            <?= htmlspecialchars($kat['NamaKategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-indigo-400">Tekan Ctrl untuk pilih lebih dari satu</small>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="toggleModal('modalEditBuku')" class="px-4 py-2 bg-indigo-700 hover:bg-indigo-600 text-white rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-700 hover:bg-indigo-600 text-white rounded-lg">Simpan</button>
            </div>
        </form>
        <button onclick="toggleModal('modalEditBuku')" class="absolute top-3 right-3 text-indigo-300 hover:text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalTambahBuku" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 overflow-y-hidden">
    <div class="bg-indigo-900/50 border border-indigo-500/70 rounded-2xl w-full max-w-lg mx-4 p-6 shadow-lg space-y-6 relative max-h-[80vh] overflow-y-auto">
        <h2 class="text-xl font-semibold text-indigo-300">Tambah Buku Baru</h2>
        <form action="pages/process/tambah_buku.php" method="POST" class="space-y-4">
            <div>
                <label for="judul" class="block font-semibold text-indigo-200">Judul Buku</label>
                <input type="text" name="judul" id="judul" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 text-indigo-900 bg-gray-400" required>
            </div>
            <div>
                <label for="kategori" class="block font-semibold text-indigo-200">Kategori Buku</label>
                <select name="kategori[]" id="kategori" multiple class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 text-indigo-900 bg-gray-400">
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= $kat['KategoriID'] ?>"><?= htmlspecialchars($kat['NamaKategori']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-indigo-400">Tekan Ctrl untuk pilih lebih dari satu</small>
            </div>

            <div>
                <label for="penulis" class="block font-semibold text-indigo-200">Penulis</label>
                <input type="text" name="penulis" id="penulis" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 text-indigo-900 bg-gray-400" required>
            </div>
            <div>
                <label for="penerbit" class="block font-semibold text-indigo-200">Penerbit</label>
                <input type="text" name="penerbit" id="penerbit" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 text-indigo-900 bg-gray-400">
            </div>
            <div>
                <label for="tahun" class="block font-semibold text-indigo-200">Tahun Terbit</label>
                <input type="number" name="tahun" id="tahun" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 text-indigo-900 bg-gray-400">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="toggleModal('modalTambahBuku')" class="px-4 py-2 bg-indigo-700 hover:bg-indigo-600 text-white rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-700 hover:bg-indigo-600 text-white rounded-lg">Simpan</button>
            </div>
        </form>
        <!-- Tombol close pojok -->
        <button onclick="toggleModal('modalTambahBuku')" class="absolute top-3 right-3 text-indigo-300 hover:text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>



<script>
    $('#tabel-buku').DataTable({
        responsive: {
            details: {
                type: 'column',
                target: 0 // target kolom mana yang mau dijadikan pemicu expand
            }
        },
        columnDefs: [
            {
                className: 'control',
                orderable: false,
                targets: 0 // kolom pertama buat icon +
            }
        ],
        order: [1, 'asc'], // biar kolom pertama (yang berisi +) nggak ikut di-sort
        pageLength: 10,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: "←",
                next: "→"
            },
            zeroRecords: "Data tidak ditemukan",
        }
    });
</script>
<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);  
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
<script>
    function openEditModal(data) {
        document.getElementById('edit-id').value = data.id;
        document.getElementById('edit-judul').value = data.judul;
        document.getElementById('edit-penulis').value = data.penulis;
        document.getElementById('edit-penerbit').value = data.penerbit;
        document.getElementById('edit-tahun').value = data.tahun;
        document.getElementById('edit-kategori').value = data.kategori;
        toggleModal('modalEditBuku');
    }
</script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('pages/process/hapus_buku.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menghapus.', 'error');
                    console.error(error);
                });
            }
        });
    }
</script>
