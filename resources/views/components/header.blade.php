<header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 relative">
    <div class="flex items-center justify-between">
        <!-- Search Bar -->
        <div class="flex items-center space-x-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                       placeholder="Miskirin apa?...."
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 w-80"
                       id="searchInput">
                <button class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="performSearch()">
                    <span class="bg-red-600 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-red-700 transition-colors duration-200">Cari</span>
                </button>
            </div>
        </div>

        <!-- Right Header -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
            </button>

            <!-- Month Filter -->
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200" onclick="toggleMonthFilter()">
                <i class="fas fa-calendar-alt mr-2"></i>
                <span id="monthText">Bulanan</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </button>

            <!-- Filter Button -->
            <button class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200" onclick="toggleFilter()">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>

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
                <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden z-50">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i>Settings
                    </a>
                    <div class="border-t border-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Filter Dropdown -->
    <div id="monthFilter" class="absolute right-20 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden z-50">
        @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $month)
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectMonth('{{ $month }}')">{{ $month }}</a>
        @endforeach
    </div>
</header>

@push('scripts')
<script>
function toggleUserMenu() {
    document.getElementById('userMenu').classList.toggle('hidden');
}
function toggleMonthFilter() {
    document.getElementById('monthFilter').classList.toggle('hidden');
}
function selectMonth(month) {
    document.getElementById('monthText').textContent = month;
    document.getElementById('monthFilter').classList.add('hidden');
}
function toggleFilter() {
    alert('Filter functionality to be implemented');
}
function performSearch() {
    const searchTerm = document.getElementById('searchInput').value;
    if (searchTerm.trim() !== '') {
        alert('Mencari: ' + searchTerm);
    }
}
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        document.getElementById('userMenu').classList.add('hidden');
        document.getElementById('monthFilter').classList.add('hidden');
    }
});
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') performSearch();
});
</script>
@endpush
