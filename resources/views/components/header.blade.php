<header class="fixed top-0 left-64 right-0 bg-white backdrop-blur-sm border-b border-gray-200/50 px-8 py-10 z-30 h-24 shadow-sm">
    <div class="flex items-center justify-between h-full">
        <!-- Welcome Section -->
        <div class="flex items-center space-x-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                <p class="text-sm text-gray-500">Selamat datang kembali, Admin</p>
            </div>
        </div>

        <!-- Right Header -->
        <div class="flex items-center space-x-6">
            <!-- Quick Stats -->
            <div class="hidden lg:flex items-center space-x-6 text-sm">
                
            </div>

            <!-- Divider -->
            <div class="hidden  w-px h-8 bg-gray-200"></div>

            <!-- Notifications -->
            <div class="relative">
                <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all duration-200">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">3</span>
                </button>
            </div>

            <!-- User Menu -->
        <div class="relative">
                <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition-colors duration-200" onclick="toggleUserMenu()">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
            <span class="font-medium">{{ auth()->user()->name ?? 'User' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <!-- User Dropdown Menu -->
                <div id="userMenu" class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 hidden z-50">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="font-semibold text-gray-800">Admin</p>
                        <p class="text-sm text-gray-500">admin@katana.com</p>
                    </div>
                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-user mr-3 w-4"></i>Profile Saya
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-cog mr-3 w-4"></i>Pengaturan
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-question-circle mr-3 w-4"></i>Bantuan
                    </a>
                    <div class="border-t border-gray-100 mt-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-3 w-4"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
function toggleUserMenu() {
    document.getElementById('userMenu').classList.toggle('hidden');
}
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        document.getElementById('userMenu').classList.add('hidden');
    }
});
</script>
@endpush
