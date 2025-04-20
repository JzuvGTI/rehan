<?php
include '../../config/koneksi.php';

try {
    $stmt = $pdo->query("
        SELECT 
            p.PeminjamanID, 
            u.NamaLengkap, 
            b.Judul, 
            b.Penulis,
            b.Penerbit,
            b.TahunTerbit,
            p.TanggalPeminjaman, 
            p.TanggalPengembalian
        FROM peminjaman p
        JOIN user u ON p.UserID = u.UserID
        JOIN buku b ON p.BukuID = b.BukuID
        ORDER BY p.TanggalPeminjaman DESC
    ");
    $dataPeminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Gagal mengambil data peminjaman: " . $e->getMessage();
    $dataPeminjaman = [];
}
?>

<section class="space-y-6">
    <h2 class="text-2xl font-bold text-indigo-100">Data Peminjaman Buku</h2>
    <div class="overflow-x-auto rounded-xl">
    <table id="tabelPeminjaman" class="min-w-full text-sm text-indigo-100 border border-indigo-500/20">
        <thead class="bg-indigo-800 text-indigo-100">
            <tr>
                <th class="px-4 py-3 text-left">No</th>
                <th class="px-4 py-3 text-left">Nama Peminjam</th>
                <th class="px-4 py-3 text-left">Judul Buku</th>
                <th class="px-4 py-3 text-left">Penulis</th>
                <th class="px-4 py-3 text-left">Tahun Terbit</th>
                <th class="px-4 py-3 text-left">Tanggal Pinjam</th>
                <th class="px-4 py-3 text-left">Tanggal Kembali</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($dataPeminjaman as $data): ?>
                <?php
                    $tanggalKembali = $data['TanggalPengembalian'];
                    $isReturned = !is_null($tanggalKembali);
                    $status = $isReturned ? 'Dikembalikan' : 'Dipinjam';
                    $tampilTanggalKembali = $isReturned ? date("d M Y", strtotime($tanggalKembali)) : 'Belum Dikembalikan';
                ?>
                <tr class="border-t border-indigo-500/10 hover:bg-indigo-800/30 transition duration-300">
                    <td class="px-4 py-3"><?= $no++ ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($data['NamaLengkap']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($data['Judul']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($data['Penulis']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($data['TahunTerbit']) ?></td>
                    <td class="px-4 py-3"><?= date("d M Y", strtotime($data['TanggalPeminjaman'])) ?></td>
                    <td class="px-4 py-3"><?= $tampilTanggalKembali ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $isReturned ? 'bg-green-600 text-white' : 'bg-yellow-500 text-black' ?>">
                            <?= $status ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <?php if (!$isReturned): ?>
                            <form onsubmit="return false;" class="form-kembalikan" data-id="<?= $data['PeminjamanID'] ?>">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-semibold">
                                    Kembalikan
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="text-sm italic text-gray-400">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#tabelPeminjaman').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            },
            zeroRecords: "Data tidak ditemukan",
            infoEmpty: "Tidak ada data tersedia",
        }
    });

    // SweetAlert konfirmasi
    $('.form-kembalikan').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const peminjamanId = form.data('id');

        Swal.fire({
            title: 'Konfirmasi Pengembalian',
            text: "Yakin ingin mengembalikan buku ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kembalikan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('pages/process/kembalikan_buku.php', { peminjaman_id: peminjamanId }, function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Buku berhasil dikembalikan.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }).fail(function() {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat mengembalikan.', 'error');
                });
            }
        });
    });
});
</script>