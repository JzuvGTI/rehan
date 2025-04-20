<?php
include '../../config/koneksi.php'; 

// Get total books
$query_books = "SELECT COUNT(*) FROM buku";
$stmt_books = $pdo->prepare($query_books);
$stmt_books->execute();
$total_books = $stmt_books->fetchColumn();

// Get active borrowings
$query_borrowings = "SELECT COUNT(*) FROM peminjaman WHERE StatusPeminjaman = 'Dipinjam'";
$stmt_borrowings = $pdo->prepare($query_borrowings);
$stmt_borrowings->execute();
$active_borrowings = $stmt_borrowings->fetchColumn();

// Get total users
$query_users = "SELECT COUNT(*) FROM user";
$stmt_users = $pdo->prepare($query_users);
$stmt_users->execute();
$total_users = $stmt_users->fetchColumn();

// Get total borrowings
$query_total_borrowings = "SELECT COUNT(*) FROM peminjaman";
$stmt_total_borrowings = $pdo->prepare($query_total_borrowings);
$stmt_total_borrowings->execute();
$total_borrowings = $stmt_total_borrowings->fetchColumn();

// Get borrowings count by month
$query_borrowings_month = "SELECT MONTH(TanggalPeminjaman) AS month, COUNT(*) AS total_borrowings 
                           FROM peminjaman
                           GROUP BY MONTH(TanggalPeminjaman)
                           ORDER BY MONTH(TanggalPeminjaman)";
$stmt_borrowings_month = $pdo->prepare($query_borrowings_month);
$stmt_borrowings_month->execute();
$borrowings_data = $stmt_borrowings_month->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for the chart
$months = [];
$total_borrowings_per_month = [];
foreach ($borrowings_data as $data) {
    $months[] = date('F', mktime(0, 0, 0, $data['month'], 10));  // Get month name
    $total_borrowings_per_month[] = $data['total_borrowings'];
}

?>
<section class="px-6 py-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Buku -->
        <div class="bg-indigo-900/30 p-6 rounded-xl shadow-lg flex justify-between items-center">
            <div>
                <h2 class="text-indigo-300 text-sm">Total Buku</h2>
                <p class="text-3xl font-bold"><?php echo $total_books; ?></p>
            </div>
            <i class="fa-solid fa-book text-indigo-300 text-3xl"></i>
        </div>

        <!-- Peminjaman Aktif -->
        <div class="bg-indigo-900/30 p-6 rounded-xl shadow-lg flex justify-between items-center">
            <div>
                <h2 class="text-indigo-300 text-sm">Peminjaman Aktif</h2>
                <p class="text-3xl font-bold"><?php echo $active_borrowings; ?></p>
            </div>
            <i class="fa-solid fa-book-reader text-indigo-300 text-3xl"></i>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-indigo-900/30 p-6 rounded-xl shadow-lg flex justify-between items-center">
            <div>
                <h2 class="text-indigo-300 text-sm">Total Pengguna</h2>
                <p class="text-3xl font-bold"><?php echo $total_users; ?></p>
            </div>
            <i class="fa-solid fa-users text-indigo-300 text-3xl"></i>
        </div>

        <!-- Total Peminjaman -->
        <div class="bg-indigo-900/30 p-6 rounded-xl shadow-lg flex justify-between items-center">
            <div>
                <h2 class="text-indigo-300 text-sm">Total Peminjaman</h2>
                <p class="text-3xl font-bold"><?php echo $total_borrowings; ?></p>
            </div>
            <i class="fa-solid fa-layer-group text-indigo-300 text-3xl"></i>
        </div>
    </div>

</section>
