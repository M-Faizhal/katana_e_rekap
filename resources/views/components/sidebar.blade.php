<div class="fixed left-0 top-0 w-64 h-full bg-white shadow-xl z-40">
    <!-- Logo/Header -->
    <div class="p-6 border-b border-red-500">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-white backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Katana" class="w-full h-full object-contain">
            </div>
            <div class="text-gray-800">
                <h1 class="text-xl font-bold tracking-wide">KATANA</h1>
                <p class="text-sm text-red-700">PT. Kamil Trio Niaga</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="mt-6 pb-20 overflow-y-auto" style="height: calc(100vh - 200px);">
        <ul class="space-y-1 px-4">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center space-x-3 text-gray-800 hover:text-red-800 rounded-xl px-4 py-3 transition-all group {{ request()->routeIs('dashboard') ? 'bg-red-200 text-red-800' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>
            
            <!-- Laporan -->
            <li>
                <a href="{{ route('laporan') }}"
                   class="flex items-center space-x-3 text-gray-800 hover:text-red-800 rounded-xl px-4 py-3 transition-all group {{ request()->routeIs('laporan') ? 'bg-red-200 text-red-800' : '' }}">
                    <i class="fas fa-file-alt w-5 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-medium">Laporan</span>
                </a>
            </li>
            
            <!-- Marketing Dropdown -->
            <li x-data="{ open: {{ request()->routeIs('marketing*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full text-gray-800 hover:text-red-800 rounded-xl px-4 py-3 transition-all group {{ request()->routeIs('marketing*') ? 'bg-red-200 text-red-800' : '' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-bullhorn w-5 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="font-medium">Marketing</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <!-- Submenu -->
                <ul x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-2 ml-6 space-y-1">
                    <li>
                        <a href="{{ route('marketing.penawaran') }}"
                           class="flex items-center space-x-3 text-gray-700 hover:text-red-800 rounded-lg px-4 py-2 text-sm transition-all group {{ request()->routeIs('marketing.penawaran') ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas fa-handshake w-4 text-sm group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-medium">Penawaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('marketing.wilayah') }}"
                           class="flex items-center space-x-3 text-gray-700 hover:text-red-800 rounded-lg px-4 py-2 text-sm transition-all group {{ request()->routeIs('marketing.wilayah') ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas fa-map-marker-alt w-4 text-sm group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-medium">Wilayah</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('marketing.potensi') }}"
                           class="flex items-center space-x-3 text-gray-700 hover:text-red-800 rounded-lg px-4 py-2 text-sm transition-all group {{ request()->routeIs('marketing.potensi') ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas fa-chart-line w-4 text-sm group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-medium">Potensi</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Purchasing Dropdown -->
            <li x-data="{ open: {{ request()->routeIs('purchasing*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full text-gray-800 hover:text-red-800 rounded-xl px-4 py-3 transition-all group {{ request()->routeIs('purchasing*') ? 'bg-red-200 text-red-800' : '' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-shopping-cart w-5 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="font-medium">Purchasing</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <!-- Submenu -->
                <ul x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-2 ml-6 space-y-1">
                    <li>
                        <a href="{{ route('purchasing.produk') }}"
                           class="flex items-center space-x-3 text-gray-700 hover:text-red-800 rounded-lg px-4 py-2 text-sm transition-all group {{ request()->routeIs('purchasing.produk') ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas fa-box w-4 text-sm group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-medium">Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchasing.vendor') }}"
                           class="flex items-center space-x-3 text-gray-700 hover:text-red-800 rounded-lg px-4 py-2 text-sm transition-all group {{ request()->routeIs('purchasing.vendor') ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas fa-truck w-4 text-sm group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-medium">Vendor</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchasing.kalkulasi') }}"
                           class="flex items-center space-x-3 text-gray-700 hover:text-red-800 rounded-lg px-4 py-2 text-sm transition-all group {{ request()->routeIs('purchasing.kalkulasi') ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas fa-calculator w-4 text-sm group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-medium">Kalkulasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchasing.pembayaran') }}"
                           class="flex items-center space-x-3 text-gray-700 hover:text-red-800 rounded-lg px-4 py-2 text-sm transition-all group {{ request()->routeIs('purchasing.pembayaran') ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas fa-credit-card w-4 text-sm group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-medium">Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchasing.pengiriman') }}"
                           class="flex items-center space-x-3 text-gray-700 hover:text-red-800 rounded-lg px-4 py-2 text-sm transition-all group {{ request()->routeIs('purchasing.pengiriman') ? 'bg-red-100 text-red-800' : '' }}">
                            <i class="fas fa-shipping-fast w-4 text-sm group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-medium">Pengiriman</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Keuangan -->
            <li>
                <a href="{{ route('keuangan') }}"
                   class="flex items-center space-x-3 text-gray-800 hover:text-red-800 rounded-xl px-4 py-3 transition-all group {{ request()->routeIs('keuangan') ? 'bg-red-200 text-red-800' : '' }}">
                    <i class="fas fa-coins w-5 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-medium">Keuangan</span>
                </a>
            </li>
            
            <!-- Produk -->
            <li>
                <a href="{{ route('produk') }}"
                   class="flex items-center space-x-3 text-gray-800 hover:text-red-800 rounded-xl px-4 py-3 transition-all group {{ request()->routeIs('produk') ? 'bg-red-200 text-red-800' : '' }}">
                    <i class="fas fa-box w-5 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-medium">Produk</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- Bottom Menu -->
    <div class="absolute bottom-0 w-64 px-4 pb-6  z-50 bg-white">
        <div class="border-t border-white/20 pt-6">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('pengaturan') }}" class="flex items-center space-x-3 text-gray-800 hover:text-red-800 rounded-xl px-4 py-3 transition-all group {{ request()->routeIs('pengaturan') ? 'bg-red-200 text-red-800' : '' }}">
                        <i class="fas fa-cog w-5 text-lg group-hover:rotate-180 transition-transform duration-500"></i>
                        <span class="font-medium">Pengaturan</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 text-gray-800 hover:text-red-800 rounded-xl px-4 py-3 transition-all group">
                            <i class="fas fa-sign-out-alt w-5 text-lg group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="font-medium">Keluar</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>