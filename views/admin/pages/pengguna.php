<?php
// Mulai output buffering
ob_start();

// Include koneksi database
include '../../config/koneksi.php'; 

// Ambil data pengguna
$sql = "SELECT * FROM user LIMIT 3";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil role dari sesi
$role = $_SESSION['user']['role'] ?? 0;

// Cek apakah role adalah admin (role 3)
if ($role != 3) {
    // Jika bukan admin, tampilkan SweetAlert dan redirect setelahnya
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: 'Anda tidak memiliki hak akses untuk halaman ini!',
        }).then(function() {
            window.location.href = 'index.php?page=dashboard';
        });
    </script>";
    exit;
}

// Selesaikan output buffering dan kirim output ke browser
ob_end_flush();
?>


<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6">
        <div class="relative">
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Cari pengguna (min 3 huruf)..." 
                class="w-full px-4 py-3 bg-indigo-900/20 border border-indigo-500/50 text-indigo-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-indigo-400"
                onkeyup="liveSearch()"
            >
            <i class="fa-solid fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-indigo-400"></i>
        </div>
    </div>

    <div id="noResults" class="hidden text-center text-indigo-300 bg-indigo-900/10 backdrop-blur-lg border border-indigo-500/30 rounded-xl p-6 shadow-xl">
        <i class="fa-solid fa-exclamation-circle text-2xl text-indigo-400 mb-2"></i>
        <p>Pengguna tidak ditemukan</p>
    </div>

    <section id="userGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($users as $user): ?>
        <div data-aos="zoom-in" class="user-card relative bg-indigo-900/10 backdrop-blur-lg border border-indigo-500/30 rounded-xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" 
             data-search="<?= htmlspecialchars(strtolower($user['NamaLengkap'] . ' ' . $user['Username'] . ' ' . $user['Email'] . ' ' . ($user['Alamat'] ?? ''))) ?>">
            <!-- Card Header with Profile -->
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Profile Picture" class="w-14 h-14 rounded-full border-2 border-indigo-400 ring-2 ring-indigo-600/50">
                    <!-- Online Indicator -->
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-indigo-900"></span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-indigo-100"><?= htmlspecialchars($user['NamaLengkap']) ?></h3>
                    <p class="text-sm text-indigo-400">@<?= htmlspecialchars($user['Username']) ?></p>
                </div>
            </div>

            <div class="mt-4 space-y-2">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-envelope text-indigo-400"></i>
                    <p class="text-sm text-indigo-200"><?= htmlspecialchars($user['Email']) ?></p>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-map-marker-alt text-indigo-400"></i>
                    <p class="text-sm text-indigo-200"><?= htmlspecialchars($user['Alamat']) ?: 'Alamat tidak tersedia' ?></p>
                </div>
            </div>

            <div class="mt-4">
                <?php 
                    $role = $user['role'];
                    if ($role == 1) {
                        $roleLabel = "User";
                        $roleIcon = "fa-solid fa-user";
                        $roleColor = "bg-gradient-to-r from-green-500 to-green-600";
                    } elseif ($role == 2) {
                        $roleLabel = "Pustakawan";
                        $roleIcon = "fa-solid fa-book";
                        $roleColor = "bg-gradient-to-r from-blue-500 to-blue-600";
                    } elseif ($role == 3) {
                        $roleLabel = "Admin";
                        $roleIcon = "fa-solid fa-shield-halved";
                        $roleColor = "bg-gradient-to-r from-red-500 to-red-600";
                    }
                ?>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-white rounded-full <?= $roleColor ?> shadow-md">
                    <i class="<?= $roleIcon ?> mr-1"></i>
                    <?= $roleLabel ?>
                </span>
            </div>

            <!-- Role Dropdown and Update Button -->
            <div class="mt-6 space-y-4">
                <div>
                    <label for="role-<?= $user['UserID'] ?>" class="text-sm text-indigo-300">Ubah Role</label>
                    <select id="role-<?= $user['UserID'] ?>" class="w-full mt-1 px-3 py-2 bg-indigo-900/20 border border-indigo-500/50 text-indigo-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option class="bg-gray-600" value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>User</option>
                        <option class="bg-gray-600" value="2" <?= $user['role'] == 2 ? 'selected' : '' ?>>Pustakawan</option>
                        <option class="bg-gray-600" value="3" <?= $user['role'] == 3 ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <button class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200" onclick="updateRole(<?= $user['UserID'] ?>)">
                    <i class="fa-solid fa-sync-alt mr-2"></i> Perbarui
                </button>
            </div>

            <!-- Decorative Element -->
            <div class="absolute top-0 right-0 w-20 h-20 bg-indigo-500/10 rounded-bl-full"></div>
        </div>
        <?php endforeach; ?>
    </section>
</div>


<script>
    function updateRole(userId) {
        const role = document.getElementById('role-' + userId).value;
        fetch('pages/process/update_role.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userId: userId, role: role })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Role pengguna berhasil diperbarui.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    background: '#1E293B',
                    color: '#D1D5DB'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message || 'Role pengguna gagal diperbarui.',
                    icon: 'error',
                    confirmButtonText: 'Coba Lagi',
                    background: '#1E293B',
                    color: '#D1D5DB'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Oops!',
                text: 'Terjadi kesalahan saat memperbarui role: ' + error.message,
                icon: 'error',
                confirmButtonText: 'Coba Lagi',
                background: '#1E293B',
                color: '#D1D5DB'
            });
        });
    }

    function liveSearch() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.getElementsByClassName('user-card');
        const noResults = document.getElementById('noResults');
        let visibleCards = 0;

        // Hanya jalankan pencarian jika input >= 3 karakter
        if (input.length < 3 && input.length > 0) {
            noResults.classList.add('hidden');
            for (let i = 0; i < cards.length; i++) {
                cards[i].style.display = 'block';
            }
            return;
        }

        for (let i = 0; i < cards.length; i++) {
            const card = cards[i];
            const searchData = card.getAttribute('data-search');

            if (input.length === 0 || searchData.includes(input)) {
                card.style.display = 'block';
                visibleCards++;
            } else {
                card.style.display = 'none';
            }
        }

        // Tampilkan pesan "Pengguna tidak ditemukan" jika tidak ada kartu yang cocok
        noResults.classList.toggle('hidden', visibleCards > 0);
    }
</script>