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

    $isActive = function (string $routeNameOrPath) {
        if (str_contains($routeNameOrPath, '/')) {
            $p = trim($routeNameOrPath, '/');
            return request()->is($p) || request()->is($p . '/*');
        }

        if (request()->routeIs($routeNameOrPath) || request()->routeIs($routeNameOrPath . '.*')) {
            return true;
        }

        $pos = strpos($routeNameOrPath, '.');
        $prefix = $pos === false ? $routeNameOrPath : substr($routeNameOrPath, 0, $pos);

        return $prefix ? request()->routeIs($prefix . '.*') : false;
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
<aside class="w-full md:w-64 h-full md:h-[calc(100vh-5rem)] pt-3 overflow-y-auto">


    <nav class="px-3 py-3 space-y-2">
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
                    <a class="flex items-center px-4 py-3  rounded-xl text-sm {{ $isActive($route) ? 'bg-active font-semibold' : 'hover:bg-gray-100' }}"
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
                    $groupLogo = $item['logo'] ?? '';
                    $groupIconPath = resource_path('icon/' . $groupLogo . '.svg');
                @endphp

                @if (count($children))
                    <details class="rounded-xl bg-white" {{ $open ? 'open' : '' }}>
                        <summary
                            class="flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl cursor-pointer select-none hover:bg-gray-100">
                            <div class="flex items-center gap-2">
                                @if ($groupLogo && file_exists($groupIconPath))
                                    <div class="w-6 shrink-0 [&_svg]:h-full [&_path]:stroke-current">
                                        {!! file_get_contents($groupIconPath) !!}
                                    </div>
                                @endif
                                <span>{{ $label }}</span>
                            </div>

                            <svg class="w-4 h-4 transition-transform duration-200 group-open:rotate-180"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <div class="pl-2 pr-2 pb-2 pt-1 space-y-1">
                            @foreach ($children as $ch)
                                @php
                                    $route = $ch['route'] ?? '';
                                    $logo = $ch['logo'] ?? '';
                                    $childLabel = $ch['label'] ?? '-';

                                    $is_active = $route && $isActive($route);

                                    $svgPath = resource_path('icon/' . $logo . '.svg');
                                    $pngPath = resource_path('icon/' . $logo . '_solid.png'); // icon active (png)

                                    $pngUrl = Vite::asset('resources/icon/' . $logo . '_solid.png');
                                @endphp

                                @if ($route)
                                    <a href="{{ route($route) }}"
                                        class="flex items-center px-4 py-3 rounded-xl text-sm {{ $is_active ? 'bg-active font-bold' : 'hover:bg-gray-100' }}">

                                        <div
                                            class="w-5 h-5 me-1 shrink-0 flex items-center justify-center
                {{ $is_active ? '' : 'svg-active' }}
                [&_svg]:w-5 [&_svg]:h-5 [&_svg]:block
                [&_svg]:overflow-visible
                [&_path]:stroke-current">

                                            @if ($is_active)
                                                @if ($logo && file_exists($pngPath))
                                                    <img src="{{ $pngUrl }}" class="w-5 h-5 object-contain"
                                                        alt="">
                                                @endif
                                            @else
                                                @if ($logo && file_exists($svgPath))
                                                    {!! file_get_contents($svgPath) !!}
                                                @endif
                                            @endif
                                        </div>

                                        <span class="ml-2">{{ $childLabel }}</span>
                                    </a>
                                @endif
                            @endforeach

                        </div>

                        <div class="border-b border-gray-200 my-2  mx-2"></div>
                    </details>
                @endif
            @endif

        @endforeach
    </nav>
</aside>
