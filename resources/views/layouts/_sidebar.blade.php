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
<style>
    .bg-active {
        color: #B27F26;
        background-color: #F3E8D4
    }

    .svg-active {
        color: #B27F26;
    }
</style>
<aside class="w-64 bg-white shadow-lg z-10">


    <nav class="px-3 py-3 space-y-2 mt-3 ">
        @foreach ($menu as $item)
            @php
                $hasChildren = isset($item['children']) && is_array($item['children']);
            @endphp

            @if (!$hasChildren)
                @php
                    $izin = $item['izin'] ?? [];
                    $route = $item['route'] ?? '';
                    $label = $item['label'] ?? '-';
                    $logo_head = $item['logo'] ?? '';

                    $path_head = resource_path('icon/' . $logo_head . '.svg');
                @endphp

                @if ($route && $canAny($izin))
                    <a class="flex items-center px-4 py-3  rounded-xl text-sm {{ $isActive($route) ? 'bg-active font-bold' : 'hover:bg-gray-100' }}"
                        href="{{ route($route) }}">
                        <div
                            class="w-7 {{ $isActive($route) ? 'svg-active [&_path]:fill-current ' : '' }} [&_svg]:h-full [&_path]:stroke-current">
                            @if ($logo_head && file_exists($path_head))
                                {!! file_get_contents($path_head) !!}
                            @endif
                        </div>
                        {{ $label }}
                    </a>
                @endif
                <div class="border-b border-gray-200 my-2 mx-2"></div>
            @else
                @php
                    $label = $item['label'] ?? '-';
                    $children = $visibleChildren($item['children']);
                    $open = $anyActiveInChildren($children);
                @endphp

                @if (count($children))
                    <div class=" rounded bg-white overflow-hidden">
                        <div class="px-3 py-2 text-sm font-semibold tracking-wide">
                            {{ $label }}
                        </div>
                        <div class="p-1 space-y-1">
                            @foreach ($children as $ch)
                                @php
                                    $route = $ch['route'] ?? '';
                                    $logo = $ch['logo'] ?? '';
                                    $label = $ch['label'] ?? '-';
                                    $iconPath = resource_path('icon/' . $logo . '.svg');
                                @endphp

                                @if ($route)
                                    <a href="{{ route($route) }}"
                                        class="flex items-center px-4 py-3 rounded-xl text-sm {{ $isActive($route) ? 'bg-active font-bold' : 'hover:bg-gray-100' }}">
                                        <div
                                            class="w-6 {{ $isActive($route) ? 'svg-active ' : '' }} [&_svg]:h-full [&_path]:stroke-current">
                                            @if ($logo && file_exists($iconPath))
                                                {!! file_get_contents($iconPath) !!}
                                            @endif
                                        </div>

                                        <span class="ml-2">{{ $label }}</span>
                                    </a>
                                @endif
                            @endforeach

                        </div>
                        <div class="border-b border-gray-200 my-2 mx-2"></div>
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
