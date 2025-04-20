<?php
session_start();
if (!isset($_GET['page'])) {
    header("Location: index.php?page=pinjam-buku");
    exit;
}
$name = $_SESSION['user']['name'] ?? 'Guest';
$initial = strtoupper(substr($name, 0, 1));
$role = $_SESSION['user']['role'] ?? 0;

if ($role != 1) {
    // Jika role bukan 2 atau 3, redirect ke halaman client
    header("Location: ../admin/index.php");
    exit;
}
$page = $_GET['page'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Library Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 10px; /* Lebar scrollbar */
        height: 10px; /* Lebar scrollbar untuk horizontal */
    }

    /* Pegangan scrollbar */
    ::-webkit-scrollbar-thumb {
        background-color: #4F46E5; /* Warna pegangan scrollbar */
        border-radius: 8px; /* Membuat ujung scrollbar lebih halus */
    }

    /* Track scrollbar */
    ::-webkit-scrollbar-track {
        border-radius: 10px; /* Membuat track lebih bulat */
    }

    /* Pojok scrollbar */
    ::-webkit-scrollbar-corner {
        background-color: #F4F5F7; /* Warna pojok scrollbar */
    }


    </style>
</head>

<body class="bg-indigo-950 flex text-indigo-100 min-h-screen">
    <!-- Sidebar -->
    <?php include 'layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 p-6 md:p-10 space-y-8 w-full">
        <?php include 'layouts/header.php'; ?>

        <!-- Content of main -->
        <?php
            $filepath = "pages/{$page}.php";
            if (file_exists($filepath)) {
                include $filepath;
            } else {
                echo '<div class="flex flex-col items-center justify-center text-center p-10 animate__animated animate__fadeIn">
                        <h1 class="text-4xl font-bold text-red-400 mb-4">404</h1>
                        <p class="text-lg text-indigo-300 mb-6">Yah, halamannya nyasar ke dunia paralel 😅<br/>Yang kamu cari nggak ditemukan nih.</p>
                        <a href="index.php?page=dashboard" class="inline-flex items-center gap-2 bg-indigo-700 hover:bg-indigo-600 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-300">
                            <i class="fa-solid fa-arrow-left"></i> 
                            Balik ke Dashboard
                        </a>
                    </div>';
            }
        ?>


    </main>
</body>
<script>
    const burgerBtn = document.getElementById('burger-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    burgerBtn.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    });


    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    });
</script>

<style>
  canvas {
    background: rgba(255, 0, 0, 0.05); /* biar kelihatan */
    border: 1px dashed red; /* debug line */
  }
</style>

<script>
    AOS.init({
        duration: 1000  // Durasi animasi dalam milidetik
    });
</script>



</html>
