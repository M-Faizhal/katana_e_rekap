<div class="w-64 sidebar-gradient shadow-lg relative">
    <!-- Logo/Header -->
    <div class="p-6 border-b border-red-400">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-sword text-red-600 text-xl"></i>
            </div>
            <div class="text-white">
                <h1 class="text-lg font-bold">Dashboard PT. Kamil Trio Niaga</h1>
                <p class="text-sm text-red-200">(KATANA)</p>
                <p class="text-xs text-red-200">Halo {{ auth()->user()->name ?? 'Tamu' }}, selamat datang 🔥</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 pb-32">
        <ul class="space-y-2 px-4">
            @foreach([
                ['route'=>'dashboard','icon'=>'tachometer-alt','label'=>'Dashboard'],
                ['route'=>'laporan','icon'=>'file-alt','label'=>'Laporan'],
                ['route'=>'marketing','icon'=>'bullhorn','label'=>'Marketing'],
                ['route'=>'purchasing','icon'=>'shopping-cart','label'=>'Purchasing'],
                ['route'=>'keuangan','icon'=>'coins','label'=>'Keuangan'],
                ['route'=>'produk','icon'=>'box','label'=>'Produk']
            ] as $item)
            <li>
                <a href="{{ route($item['route']) }}"
                   class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200 {{ request()->routeIs($item['route']) ? 'bg-red-500' : '' }}">
                    <i class="fas fa-{{ $item['icon'] }} w-5"></i>
                    <span class="font-medium">{{ $item['label'] }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </nav>

    <!-- Bottom Menu -->
    <div class="absolute bottom-0 w-64 px-4 pb-6">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('pengaturan') }}" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200 {{ request()->routeIs('pengaturan') ? 'bg-red-500' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Pengaturan</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200 w-full text-left">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-medium">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
