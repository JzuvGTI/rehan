
<?php $role = $_SESSION['user']['role'] ?? null; ?>
<!-- Overlay -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed md:sticky top-0 left-0 z-40 w-64 h-screen bg-indigo-900/10 backdrop-blur-md border-r border-indigo-500/20 shadow-2xl p-6 flex flex-col justify-between transition-transform duration-300 -translate-x-full md:translate-x-0">
    <!-- Atas (Logo + Menu) -->
    <div>
        <div class="text-center mb-6">
            <i class="fa-solid fa-book-open text-3xl text-indigo-200 mb-2"></i>
            <h2 class="text-xl font-bold text-indigo-100">Library App</h2>
        </div>
        <nav class="flex flex-col space-y-2 text-sm">
            <a href="index.php?page=dashboard" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-indigo-200 hover:text-white <?php echo ($_GET['page'] == '' || $_GET['page'] == 'dashboard') ? 'bg-indigo-800/40 border-indigo-400/30' : 'bg-indigo-900/10 border-indigo-500/10'; ?> hover:bg-indigo-800/40 hover:border-indigo-400/30 transition-all duration-300 shadow-sm">
                <i class="fa-solid fa-chart-line group-hover:scale-110 transform transition-transform duration-300"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="index.php?page=buku" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-indigo-200 hover:text-white <?php echo ($_GET['page'] == 'buku') ? 'bg-indigo-800/40 border-indigo-400/30' : 'bg-indigo-900/10 border-indigo-500/10'; ?> hover:bg-indigo-800/40 hover:border-indigo-400/30 transition-all duration-300 shadow-sm">
                <i class="fa-solid fa-book group-hover:scale-110 transform transition-transform duration-300"></i>
                <span class="font-medium">Buku</span>
            </a>
            <?php if ($role == 3): // Menampilkan menu Pengguna hanya untuk role 2 dan 3 ?>
                <a href="index.php?page=pengguna" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-indigo-200 hover:text-white <?php echo ($_GET['page'] == 'pengguna') ? 'bg-indigo-800/40 border-indigo-400/30' : 'bg-indigo-900/10 border-indigo-500/10'; ?> hover:bg-indigo-800/40 hover:border-indigo-400/30 transition-all duration-300 shadow-sm">
                    <i class="fa-solid fa-users group-hover:scale-110 transform transition-transform duration-300"></i>
                    <span class="font-medium">Pengguna</span>
                </a>
            <?php endif; ?>
            <a href="index.php?page=peminjaman" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-indigo-200 hover:text-white <?php echo ($_GET['page'] == 'peminjaman') ? 'bg-indigo-800/40 border-indigo-400/30' : 'bg-indigo-900/10 border-indigo-500/10'; ?> hover:bg-indigo-800/40 hover:border-indigo-400/30 transition-all duration-300 shadow-sm">
                <i class="fa-solid fa-handshake group-hover:scale-110 transform transition-transform duration-300"></i>
                <span class="font-medium">Peminjaman</span>
            </a>
        </nav>
    </div>

    <!-- Bawah (Logout) -->
    <div class="mt-6">
        <a href="../auth/logout.php" class="group w-full flex items-center justify-center gap-2 px-4 py-3 border border-indigo-500/30 bg-indigo-800/30 hover:bg-indigo-700/40 text-indigo-200 hover:text-white rounded-xl shadow-md transition-all duration-300">
            <i class="fa-solid fa-right-from-bracket group-hover:rotate-180 transform transition-transform duration-300"></i>
            Logout
        </a>
    </div>
</aside>
