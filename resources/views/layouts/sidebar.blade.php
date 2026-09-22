@php
$menu = [
    ['route' => 'dashboard',        'label' => 'Dashboard',    'icon' => '📊', 'roles' => ['Admin','Manajer Gudang','Staff Gudang']],
    ['route' => 'products.index',   'label' => 'Produk',       'icon' => '📦', 'roles' => ['Admin','Manajer Gudang']],
    ['route' => 'categories.index', 'label' => 'Kategori',     'icon' => '🏷️', 'roles' => ['Admin']],
    ['route' => 'stock.index',      'label' => 'Stok',         'icon' => '📈', 'roles' => ['Admin','Manajer Gudang','Staff Gudang']],
    ['route' => 'opname.index',     'label' => 'Stock Opname', 'icon' => '📋', 'roles' => ['Admin','Manajer Gudang','Staff Gudang']],
    ['route' => 'suppliers.index',  'label' => 'Supplier',     'icon' => '🏭', 'roles' => ['Admin','Manajer Gudang']],
    ['route' => 'users.index',      'label' => 'Pengguna',     'icon' => '👥', 'roles' => ['Admin']],
    ['route' => 'reports.index',    'label' => 'Laporan',      'icon' => '📄', 'roles' => ['Admin','Manajer Gudang']],
    ['route' => 'settings.index',   'label' => 'Pengaturan',   'icon' => '⚙️', 'roles' => ['Admin']],
];
@endphp

<aside id="logo-sidebar"
       class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($menu as $item)
                @if (in_array(auth()->user()->role, $item['roles'], true))
                    @php
                        $base = explode('.', $item['route'])[0];
                        $isActive = request()->routeIs($base . '*') || request()->routeIs($item['route']);
                    @endphp
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center p-2 rounded-lg group
                                  {{ $isActive
                                     ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                     : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="w-6 text-center text-lg">{{ $item['icon'] }}</span>
                            <span class="ms-3">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach

            <li class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900 dark:hover:text-red-200">
                        <span class="w-6 text-center text-lg">🚪</span>
                        <span class="ms-3">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>