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
    <nav class="mt-6 pb-32 overflow-y-auto" style="height: calc(100vh - 200px);">
        <ul class="space-y-1 px-4">
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
                   class="flex items-center space-x-3 text-gray-800 hover:text-red-800  rounded-xl px-4 py-3 transition-all group {{ request()->routeIs($item['route']) ? 'bg-red-200 text-red-800 ' : '' }}">
                    <i class="fas fa-{{ $item['icon'] }} w-5 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-medium">{{ $item['label'] }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </nav>

    <!-- Bottom Menu -->
    <div class="absolute bottom-0 w-64 px-4 pb-6 ">
        <div class="border-t border-white/20 pt-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('pengaturan') }}" class="flex items-center space-x-3 text-gray-800 hover:text-red-800  rounded-xl px-4 py-3 transition-all group {{ request()->routeIs('pengaturan') ? 'bg-red-200 text-red-800' : '' }}">
                        <i class="fas fa-cog w-5 text-lg group-hover:rotate-180 transition-transform duration-500"></i>
                        <span class="font-medium">Pengaturan</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 text-gray-800 hover:text-red-800  rounded-xl px-4 py-3 transition-all group">
                            <i class="fas fa-sign-out-alt w-5 text-lg group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="font-medium">Keluar</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
