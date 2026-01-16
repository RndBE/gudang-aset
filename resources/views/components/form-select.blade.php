@props(['name', 'required' => false])

<div class="relative w-full">
    <select name="{{ $name }}" {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full border border-gray-300 rounded-lg text-sm px-3 py-2 pr-10 appearance-none',
        ]) }}>
        {{ $slot }}
    </select>

    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500" viewBox="0 0 20 20"
        fill="currentColor">
        <path fill-rule="evenodd"
            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
            clip-rule="evenodd" />
    </svg>
</div>
