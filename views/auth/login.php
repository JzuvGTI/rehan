<?php
session_start();
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role == 1) {
        header('Location: ../client/index.php?page=pinjam-buku');
        exit;
    } elseif ($role == 2 || $role == 3) {
        header('Location: ../admin/index.php?page=dashboard'); 
        exit;
    }
}

// Ambil pesan sukses dan error jika ada
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Library App - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a, #0f172a);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div
        class="w-full max-w-md bg-white/5 backdrop-blur-xl rounded-3xl shadow-2xl border border-indigo-300/20 p-8 md:p-10 transition-all duration-500">
        <div class="text-center mb-8">
            <i class="fa-solid fa-book-open text-5xl text-indigo-100 drop-shadow-md mb-4 animate-pulse"></i>
            <h1 class="text-4xl font-extrabold text-white tracking-wide">Library App</h1>
            <p class="text-indigo-200 mt-2">Sign in to access your library</p>
            <?php if ($success): ?>
                <div class="mb-4 p-3 bg-green-600/20 border border-green-500 text-green-200 rounded-md shadow-sm">
                    <i class="fa-solid fa-circle-check mr-2"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
        </div>
        <form action="process/Proses-Login.php" method="post" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-indigo-200">Email Address</label>
                <div class="mt-1 relative">
                    <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-indigo-400"></i>
                    <input id="email" name="email" type="email" required
                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-indigo-400/20 rounded-lg text-indigo-100 placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300"
                        placeholder="Enter your email" />
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-indigo-200">Password</label>
                <div class="mt-1 relative">
                    <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-indigo-400"></i>
                    <input id="password" name="password" type="password" required
                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-indigo-400/20 rounded-lg text-indigo-100 placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300"
                        placeholder="Enter your password" />
                </div>
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2 text-sm text-indigo-200">
                    <input type="checkbox" id="remember"
                        class="h-4 w-4 text-indigo-500 border-indigo-500/20 bg-indigo-800 rounded" />
                    <span>Remember me</span>
                </label>
                <a href="#" class="text-sm text-indigo-300 hover:text-indigo-100 transition-colors">Forgot
                    Password?</a>
            </div>
            <button type="submit"
                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Sign In
            </button>
        </form>
        <p class="mt-6 text-center text-sm text-indigo-200">
            Don't have an account?
            <a href="register.php" class="text-indigo-300 hover:text-indigo-100 font-semibold transition-colors">Sign Up</a>
        </p>
    </div>
</body>

</html>
