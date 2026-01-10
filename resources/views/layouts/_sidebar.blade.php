@php
    $user = auth()->user();
    $menu = config('sidebar', []);

    $canAny = function (array $kodes) use ($user) {
        if (!$user) {
            return false;
        }
        if (empty($kodes)) {
            return true;
        }
        foreach ($kodes as $k) {
            if ($user->punyaIzin($k)) {
                return true;
            }
        }
        return false;
    };

    $isActive = function (string $routeName) {
        return request()->routeIs($routeName) || request()->routeIs($routeName . '.*');
    };

    $visibleChildren = function (array $children) use ($canAny) {
        $out = [];
        foreach ($children as $ch) {
            $izin = $ch['izin'] ?? [];
            if ($canAny($izin)) {
                $out[] = $ch;
            }
        }
        return $out;
    };

    $anyActiveInChildren = function (array $children) use ($isActive) {
        foreach ($children as $ch) {
            $r = $ch['route'] ?? '';
            if ($r && $isActive($r)) {
                return true;
            }
        }
        return false;
    };
@endphp

<aside class="w-64 bg-white border-r">
    <div class="p-4 border-b">
        <div class="font-semibold">Gudang Aset</div>
        <div class="text-xs text-gray-500 mt-1">
            {{ $user?->nama_lengkap ?? '-' }} · {{ $user?->username ?? '-' }}
        </div>
    </div>

    <nav class="px-2 py-3 space-y-2">
        @foreach ($menu as $item)
            @php
                $hasChildren = isset($item['children']) && is_array($item['children']);
            @endphp

            @if (!$hasChildren)
                @php
                    $izin = $item['izin'] ?? [];
                    $route = $item['route'] ?? '';
                    $label = $item['label'] ?? '-';
                @endphp

                @if ($route && $canAny($izin))
                    <a class="block px-3 py-2 rounded text-sm {{ $isActive($route) ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}"
                        href="{{ route($route) }}">
                        {{ $label }}
                    </a>
                @endif
            @else
                @php
                    $label = $item['label'] ?? '-';
                    $children = $visibleChildren($item['children']);
                    $open = $anyActiveInChildren($children);
                @endphp

                @if (count($children))
                    <div class="border rounded bg-white overflow-hidden">
                        <div
                            class="px-3 py-2 text-xs font-semibold uppercase tracking-wide {{ $open ? 'bg-gray-50' : 'bg-white' }}">
                            {{ $label }}
                        </div>
                        <div class="p-1 space-y-1">
                            @foreach ($children as $ch)
                                @php
                                    $route = $ch['route'] ?? '';
                                    $label = $ch['label'] ?? '-';
                                @endphp
                                @if ($route)
                                    <a class="block px-3 py-2 rounded text-sm {{ $isActive($route) ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}"
                                        href="{{ route($route) }}">
                                        {{ $label }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        @endforeach

        <div class="pt-2">
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 rounded text-sm hover:bg-gray-100">Logout</button>
            </form>
        </div>
    </nav>
</aside>
