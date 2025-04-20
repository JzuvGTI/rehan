<header class="flex items-center justify-between px-4 py-3 bg-indigo-900/10 backdrop-blur-md border-b border-indigo-500/20 shadow-md rounded-xl mb-6">
    <div class="flex items-center gap-4">
        <button class="md:hidden text-indigo-300 hover:text-white transition" id="burger-btn">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <h1 class="text-xl md:text-2xl font-bold text-indigo-100">Dashboard</h1>
    </div>
    <div class="flex items-center gap-4">
        <div class="hidden sm:block text-sm text-indigo-300">
            Welcome back, <span class="font-semibold text-indigo-100"><?= htmlspecialchars($name) ?>!</span>
        </div>
        <button class="relative text-indigo-300 hover:text-white transition">
            <i class="fa-solid fa-bell text-lg"></i>
            <span class="absolute top-0 right-0 bg-red-500 rounded-full w-2 h-2 animate-ping"></span>
        </button>
        <div class="w-9 h-9 rounded-full bg-indigo-700/40 border border-indigo-400/20 flex items-center justify-center text-indigo-200 text-sm font-bold cursor-pointer hover:ring-2 hover:ring-indigo-500 transition-all">
            <?= $initial ?>
        </div>
    </div>
</header>