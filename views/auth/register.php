<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Library App - Register</title>
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
            <i class="fa-solid fa-user-plus text-5xl text-indigo-100 drop-shadow-md mb-4 animate-pulse"></i>
            <h1 class="text-4xl font-extrabold text-white tracking-wide">Register</h1>
            <p class="text-indigo-200 mt-2">Create your library account</p>
            <?php session_start(); ?>
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="bg-red-500 text-white text-sm p-2 rounded mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="bg-green-500 text-white text-sm p-2 rounded mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
        </div>
        <form action="process/Proses-Register.php" method="post" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-indigo-200">Full Name</label>
                <div class="mt-1 relative">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-indigo-400"></i>
                    <input id="name" name="name" type="text" required
                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-indigo-400/20 rounded-lg text-indigo-100 placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300"
                        placeholder="Enter your full name" />
                </div>
            </div>
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
                        placeholder="Create a password" />
                </div>
            </div>
            <div>
                <label for="confirm" class="block text-sm font-medium text-indigo-200">Confirm Password</label>
                <div class="mt-1 relative">
                    <i class="fa-solid fa-lock-key absolute left-3 top-1/2 -translate-y-1/2 text-indigo-400"></i>
                    <input id="confirm" name="confirm" type="password" required
                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-indigo-400/20 rounded-lg text-indigo-100 placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300"
                        placeholder="Re-enter your password" />
                </div>
            </div>
            <div>
                <label for="alamat" class="block text-sm font-medium text-indigo-200">Address</label>
                <div class="mt-1 relative">
                    <i class="fa-solid fa-location-dot absolute left-3 top-1/2 -translate-y-1/2 text-indigo-400"></i>
                    <input id="alamat" name="alamat" type="text" required
                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-indigo-400/20 rounded-lg text-indigo-100 placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300"
                        placeholder="Enter your address" />
                </div>
            </div>

            <button type="submit"
                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300">
                <i class="fa-solid fa-user-check mr-2"></i> Create Account
            </button>
        </form>
        <p class="mt-6 text-center text-sm text-indigo-200">
            Already have an account?
            <a href="login.php" class="text-indigo-300 hover:text-indigo-100 font-semibold transition-colors">Sign In</a>
        </p>
    </div>
</body>

</html>
