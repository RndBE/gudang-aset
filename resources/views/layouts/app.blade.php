<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AWASS</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('icon/head-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<style>
    .btn-active {
        color: white;
        background-color: #C58D2A
    }


    .btn-outline-active {
        color: #C58D2A;
        border: 1.5px solid #C58D2A;

    }

    .typing-bubble {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 22px;
    }

    .typing-bubble span {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #9ca3af;
        opacity: .55;
        animation: typingDots 1.1s infinite ease-in-out;
    }

    .typing-bubble span:nth-child(2) {
        animation-delay: .15s;
    }

    .typing-bubble span:nth-child(3) {
        animation-delay: .3s;
    }

    @keyframes typingDots {

        0%,
        80%,
        100% {
            transform: translateY(0);
            opacity: .45;
        }

        40% {
            transform: translateY(-4px);
            opacity: 1;
        }
    }

    .md p {
        margin: .35rem 0;
        line-height: 1.65;
    }

    .md ul {
        list-style: disc;
        padding-left: 1.25rem;
        margin: .35rem 0;
    }

    .md ol {
        list-style: decimal;
        padding-left: 1.25rem;
        margin: .35rem 0;
    }

    .md li {
        margin: .2rem 0;
    }

    .md code {
        background: #f3f4f6;
        padding: .12rem .35rem;
        border-radius: .35rem;
    }

    .md pre {
        background: #0b1020;
        color: #e5e7eb;
        padding: .75rem;
        border-radius: .75rem;
        overflow: auto;
    }

    .md h1,
    .md h2,
    .md h3 {
        font-weight: 700;
        margin: .55rem 0 .25rem;
    }

    .md a {
        text-decoration: underline;
    }
</style>
@php
    $user = auth()->user();
@endphp

{{-- <body class="min-h-screen bg-white font-sans">
    <div class="fixed bottom-6 right-6 z-50">
        <button id="chatFab"
            class="px-5 py-3 cursor-pointer rounded-2xl bg-[#C58D2A] text-white shadow-lg hover:bg-amber-700 active:scale-95 transition flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-sparkles me-2">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                    d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm0 -12a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm-7 12a6 6 0 0 1 6 -6a6 6 0 0 1 -6 -6a6 6 0 0 1 -6 6a6 6 0 0 1 6 6z">
                </path>
            </svg>
            <div class="text-base">Tanya Awass</div>

        </button>
    </div>

    <div class="p-4 h-20 bg-white shadow-[0_1px_5px_2px_rgba(0,0,0,0.25)] sticky top-0 z-50">
        <div class="flex justify-between items-center ">
            <div class="w-6 shrink-0 [&_svg]:h-full ">
                {!! file_get_contents(resource_path('icon/icon-head.svg')) !!}
            </div>
            <div x-data="{ open: false }" class="relative ">
                <button type="button" @click="open = !open" @keydown.escape.window="open = false"
                    class="flex items-center rounded-lg px-2 py-1 hover:bg-gray-100 focus:outline-none cursor-pointer">
                    <div class="text-end me-3">
                        <div class="font-semibold">Gudang Aset</div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $user?->nama_lengkap ?? '-' }}
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-6 shrink-0 [&_svg]:h-full me-3">
                            {!! file_get_contents(resource_path('icon/avatar.svg')) !!}
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="ms-2 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </div>
                </button>

                <div x-show="open" x-transition @click.outside="open = false"
                    class="absolute right-0 mt-2 w-44 rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden z-50"
                    style="display: none;">
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="w-full text-left px-3 py-3 rounded text-sm hover:bg-gray-100 cursor-pointer">Logout</button>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <div class="flex min-h-screen items-stretch">
        <aside class="sticky bg-white top-20 self-start min-h-screen shadow-xl z-10">
            @include('layouts._sidebar')
        </aside>

        <main class="flex-1 min-min-h-screen">
            <div class="p-6 bg-white min-min-h-screen">
                @yield('content')
            </div>
        </main>
    </div>
</body>
<div id="chatOverlay" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="chatModal"
            class="w-[95vw] max-w-5xl h-[90vh] rounded-xl bg-white shadow-2xl border border-gray-200 overflow-hidden hidden">
            <div class="px-6 py-4 flex items-center justify-between bg-white border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full  text-[#C58D2A]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-sparkles">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path
                                d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm0 -12a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm-7 12a6 6 0 0 1 6 -6a6 6 0 0 1 -6 -6a6 6 0 0 1 -6 6a6 6 0 0 1 6 6z">
                            </path>
                        </svg>
                    </span>
                    <div class="font-semibold text-2xl"> {!! file_get_contents(resource_path('icon/header_chatbot.svg')) !!}</div>
                </div>

                <button id="chatClose"
                    class="h-10 w-10 rounded-full cursor-pointer hover:bg-gray-100 flex [&_svg]:h-full [&_path]:stroke-[#C58D2A] items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="h-6 w-6 text-gray-500">
                        <path fill-rule="evenodd"
                            d="M6.22 6.22a.75.75 0 0 1 1.06 0L12 10.94l4.72-4.72a.75.75 0 1 1 1.06 1.06L13.06 12l4.72 4.72a.75.75 0 1 1-1.06 1.06L12 13.06l-4.72 4.72a.75.75 0 0 1-1.06-1.06L10.94 12 6.22 7.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="h-[calc(85vh-140px)] px-6 py-6 space-y-5 bg-white overflow-y-auto">
                <div class="flex items-start gap-4">
                    <div class="h-11 w-11 rounded-full bg-[#F3E8D4] flex items-center justify-center shrink-0">
                        {!! file_get_contents(resource_path('icon/robot.svg')) !!}
                    </div>
                    <div
                        class="max-w-[70%] rounded-3xl border border-gray-200 bg-gray-50 px-5 py-4 text-base text-gray-800">
                        Halo! Apakah ada yang bisa saya bantu?
                    </div>
                </div>

                <div class="flex items-start gap-4 justify-end">
                    <div class="max-w-[70%] rounded-xl bg-[#F3E8D4] px-5 py-4 text-base text-gray-900">
                        Barang yang menipis di gudang utama apa aja?
                    </div>
                    <div
                        class="h-11 w-11 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="h-6 w-6">
                            <path fill-rule="evenodd"
                                d="M7.5 7.5a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

            </div>

            <div class="px-6 py-5  bg-white">
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <div
                            class="rounded-3xl border border-gray-200 bg-white px-5 py-4 flex items-center gap-4 shadow-sm">
                            <input id="chatInput" type="text" placeholder="Ketik pesan disini..."
                                class="w-full outline-none text-base text-gray-800 placeholder:text-gray-400" />
                            <button id="chatMic"
                                class="h-10 w-10 rounded-full hover:bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-6 w-6 text-gray-600">
                                    <path d="M12 1.5a3 3 0 0 0-3 3v7.5a3 3 0 0 0 6 0V4.5a3 3 0 0 0-3-3Z" />
                                    <path
                                        d="M6 10.5a.75.75 0 0 1 .75.75 5.25 5.25 0 0 0 10.5 0 .75.75 0 0 1 1.5 0 6.75 6.75 0 0 1-6 6.714V21a.75.75 0 0 1-1.5 0v-3.036A6.75 6.75 0 0 1 5.25 11.25.75.75 0 0 1 6 10.5Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button id="chatSend"
                        class="h-14 w-14 cursor-pointer rounded-full bg-[#C58D2A] text-white shadow-lg hover:bg-amber-700 active:scale-95 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="h-7 w-7">
                            <path
                                d="M3.478 2.405a.75.75 0 0 1 .822-.122l18 9a.75.75 0 0 1 0 1.34l-18 9A.75.75 0 0 1 3.2 20.7l2.09-6.968a.75.75 0 0 1 .69-.532H13.5a.75.75 0 0 0 0-1.5H5.98a.75.75 0 0 1-.69-.532L3.2 4.2a.75.75 0 0 1 .278-.795Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<body x-data="{ sidebarOpen: false }" class="min-h-screen bg-white font-sans">
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>

    <div class="fixed bottom-6 right-6 z-50">
        <button id="chatFab"
            class="px-5 py-3 cursor-pointer rounded-2xl bg-[#C58D2A] text-white shadow-lg hover:bg-amber-700 active:scale-95 transition flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-sparkles me-2">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                    d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm0 -12a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm-7 12a6 6 0 0 1 6 -6a6 6 0 0 1 -6 -6a6 6 0 0 1 -6 6a6 6 0 0 1 6 6z">
                </path>
            </svg>
            <div class="text-base">Tanya Awass</div>
        </button>
    </div>

    <div class="sticky top-0 inset-x-0 z-50 w-screen bg-white shadow-[0_1px_5px_2px_rgba(0,0,0,0.25)]">
        <div class="h-20 px-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button"
                    class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-gray-100"
                    @click="sidebarOpen = true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path fill-rule="evenodd"
                            d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="w-6 shrink-0 [&_svg]:h-full hidden md:block">
                    {!! file_get_contents(resource_path('icon/icon-head.svg')) !!}
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button type="button" @click="open = !open" @keydown.escape.window="open = false"
                    class="flex items-center rounded-lg px-2 py-1 hover:bg-gray-100 focus:outline-none cursor-pointer">
                    <div class="text-end me-3">
                        <div class="font-semibold">Gudang Aset</div>
                        <div class="text-xs text-gray-500 ">
                            {{ $user?->nama_lengkap ?? '-' }}
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-6 shrink-0 [&_svg]:h-full me-3">
                            {!! file_get_contents(resource_path('icon/avatar.svg')) !!}
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="ms-2 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </div>
                </button>

                <div x-show="open" x-transition @click.outside="open = false"
                    class="absolute right-0 mt-2 w-44 rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden z-50"
                    style="display: none;">
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-3 py-3 rounded text-sm hover:bg-gray-100 cursor-pointer">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="flex min-h-[calc(100vh-5rem)] items-stretch">
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40 md:hidden"
            @click="sidebarOpen = false" @keydown.escape.window="sidebarOpen = false">
        </div>

        <aside x-cloak
            class="fixed inset-y-0 left-0 z-50 w-[85vw] max-w-xs bg-white shadow-2xl md:shadow-xl
                   transform transition-transform duration-200
                   md:sticky md:top-20 md:z-10 md:w-64 md:max-w-none md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

            <div class="md:hidden flex items-center justify-between px-4 py-3 border-b border-gray-200">

                <div class="font-semibold text-sm">Menu</div>

                <button type="button"
                    class="h-10 w-10 inline-flex items-center justify-center rounded-xl hover:bg-gray-100"
                    @click="sidebarOpen = false" aria-label="Close sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path fill-rule="evenodd"
                            d="M6.22 6.22a.75.75 0 0 1 1.06 0L12 10.94l4.72-4.72a.75.75 0 1 1 1.06 1.06L13.06 12l4.72 4.72a.75.75 0 0 1-1.06 1.06L12 13.06l-4.72 4.72a.75.75 0 0 1-1.06-1.06L10.94 12 6.22 7.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="h-full md:h-auto">
                @include('layouts._sidebar')
            </div>
        </aside>

        <main class="flex-1 min-h-[calc(100vh-5rem)] min-w-0">
            <div class="p-4 sm:p-6 bg-white min-h-[calc(100vh-5rem)] min-w-0 overflow-x-hidden">
                <div class="w-full max-w-full">
                    @yield('content')
                </div>
            </div>
        </main>

    </div>
</body>

<div id="chatOverlay" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="chatModal"
            class="w-[95vw] max-w-5xl h-[90vh] rounded-xl bg-white shadow-2xl border border-gray-200 overflow-hidden hidden">
            <div class="px-6 py-4 flex items-center justify-between bg-white border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full  text-[#C58D2A]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-sparkles">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path
                                d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm0 -12a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm-7 12a6 6 0 0 1 6 -6a6 6 0 0 1 -6 -6a6 6 0 0 1 -6 6a6 6 0 0 1 6 6z">
                            </path>
                        </svg>
                    </span>
                    <div class="font-semibold text-2xl"> {!! file_get_contents(resource_path('icon/header_chatbot.svg')) !!}</div>
                </div>

                <button id="chatClose"
                    class="h-10 w-10 rounded-full cursor-pointer hover:bg-gray-100 flex [&_svg]:h-full [&_path]:stroke-[#C58D2A] items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="h-6 w-6 text-gray-500">
                        <path fill-rule="evenodd"
                            d="M6.22 6.22a.75.75 0 0 1 1.06 0L12 10.94l4.72-4.72a.75.75 0 1 1 1.06 1.06L13.06 12l4.72 4.72a.75.75 0 1 1-1.06 1.06L12 13.06l-4.72 4.72a.75.75 0 0 1-1.06-1.06L10.94 12 6.22 7.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="h-[calc(85vh-140px)] px-6 py-6 space-y-5 bg-white overflow-y-auto">
                <div class="flex items-start gap-4">
                    <div class="h-11 w-11 rounded-full bg-[#F3E8D4] flex items-center justify-center shrink-0">
                        {!! file_get_contents(resource_path('icon/robot.svg')) !!}
                    </div>
                    <div
                        class="max-w-[70%] rounded-3xl border border-gray-200 bg-gray-50 px-5 py-4 text-base text-gray-800">
                        Halo! Apakah ada yang bisa saya bantu?
                    </div>
                </div>


            </div>

            <div class="px-6 py-5  bg-white">
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <div
                            class="rounded-3xl border border-gray-200 bg-white px-5 py-4 flex items-center gap-4 shadow-sm">
                            <input id="chatInput" type="text" placeholder="Ketik pesan disini..."
                                class="w-full outline-none text-base text-gray-800 placeholder:text-gray-400" />
                            <button id="chatMic"
                                class="h-10 w-10 rounded-full hover:bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-6 w-6 text-gray-600">
                                    <path d="M12 1.5a3 3 0 0 0-3 3v7.5a3 3 0 0 0 6 0V4.5a3 3 0 0 0-3-3Z" />
                                    <path
                                        d="M6 10.5a.75.75 0 0 1 .75.75 5.25 5.25 0 0 0 10.5 0 .75.75 0 0 1 1.5 0 6.75 6.75 0 0 1-6 6.714V21a.75.75 0 0 1-1.5 0v-3.036A6.75 6.75 0 0 1 5.25 11.25.75.75 0 0 1 6 10.5Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button id="chatSend"
                        class="h-14 w-14 cursor-pointer rounded-full bg-[#C58D2A] text-white shadow-lg hover:bg-amber-700 active:scale-95 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="h-7 w-7">
                            <path
                                d="M3.478 2.405a.75.75 0 0 1 .822-.122l18 9a.75.75 0 0 1 0 1.34l-18 9A.75.75 0 0 1 3.2 20.7l2.09-6.968a.75.75 0 0 1 .69-.532H13.5a.75.75 0 0 0 0-1.5H5.98a.75.75 0 0 1-.69-.532L3.2 4.2a.75.75 0 0 1 .278-.795Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.8/dist/purify.min.js"></script>
<script>
    const chatBox = document.querySelector('#chatOverlay .h-\\[calc\\(85vh-140px\\)\\]')
    const chatInput = document.getElementById('chatInput')
    const chatSend = document.getElementById('chatSend')

    const API_URL = 'http://72.60.78.159:8001/chat_stream'
    const history = []

    function appendBubble(role, text) {
        const wrap = document.createElement('div')
        if (role === 'user') {
            wrap.className = 'flex items-start gap-4 justify-end'
            wrap.innerHTML = `
      <div class="max-w-[70%] rounded-xl bg-[#F3E8D4] px-5 py-4 text-base text-gray-900"></div>
      <div class="h-11 w-11 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
          <path fill-rule="evenodd" d="M7.5 7.5a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
        </svg>
      </div>
    `
            wrap.querySelector('div').textContent = text
            chatBox.appendChild(wrap)
            chatBox.scrollTop = chatBox.scrollHeight
            return {
                wrap,
                bubble: wrap.querySelector('div')
            }
        } else {
            wrap.className = 'flex items-start gap-4'
            wrap.innerHTML = `
      <div class="h-11 w-11 rounded-full bg-[#F3E8D4] flex items-center justify-center shrink-0">
        {!! str_replace(["\n", "\r"], '', file_get_contents(resource_path('icon/robot.svg'))) !!}
      </div>
      <div class="max-w-[70%] rounded-3xl border border-gray-200 bg-gray-50 px-5 py-4 text-base text-gray-800 whitespace-pre-wrap"></div>
    `
            wrap.querySelector('div:last-child').textContent = text
            chatBox.appendChild(wrap)
            chatBox.scrollTop = chatBox.scrollHeight
            return {
                wrap,
                bubble: wrap.querySelector('div:last-child')
            }
        }
    }

    function appendTyping() {
        const wrap = document.createElement('div')
        wrap.className = 'flex items-start gap-4'
        wrap.innerHTML = `
    <div class="h-11 w-11 rounded-full bg-[#F3E8D4] flex items-center justify-center shrink-0">
      {!! str_replace(["\n", "\r"], '', file_get_contents(resource_path('icon/robot.svg'))) !!}
    </div>
    <div class="max-w-[70%] rounded-3xl border border-gray-200 bg-gray-50 px-5 py-4 text-base text-gray-800">
      <div class="typing-bubble" aria-label="AI sedang mengetik">
        <span></span><span></span><span></span>
      </div>
    </div>
  `
        chatBox.appendChild(wrap)
        chatBox.scrollTop = chatBox.scrollHeight
        return wrap
    }

    function setLoading(isLoading) {
        chatSend.disabled = isLoading
        chatInput.disabled = isLoading
        chatSend.classList.toggle('opacity-60', isLoading)
        chatSend.classList.toggle('cursor-not-allowed', isLoading)
    }

    function stageLabel(stage) {
        if (stage === 'start') return 'Mulai...'
        if (stage === 'detect_intent') return 'Klasifikasi intent...'
        if (stage === 'intent_done') return 'Intent didapat...'
        if (stage === 'fetch_backend') return 'Ambil data backend...'
        if (stage === 'llm_stream') return 'Generate jawaban...'
        return stage ? `Proses: ${stage}` : ''
    }

    function ensureAssistantBubble(typingEl, assistantBubbleRef) {
        if (assistantBubbleRef.current) return assistantBubbleRef.current
        typingEl?.remove()
        assistantBubbleRef.current = appendBubble('assistant', '')
        return assistantBubbleRef.current
    }

    function parseSSEChunk(chunkText) {
        const events = []
        const blocks = chunkText.split(/\n\n/)
        for (const block of blocks) {
            const lines = block.split(/\n/)
            for (const ln of lines) {
                const line = ln.trim()
                if (!line.startsWith('data:')) continue
                const jsonStr = line.replace(/^data:\s*/, '')
                try {
                    events.push(JSON.parse(jsonStr))
                } catch (_) {}
            }
        }
        return events
    }

    async function sendMessage() {
        const text = (chatInput.value || '').trim()
        if (!text) return

        chatInput.value = ''
        appendBubble('user', text)
        history.push({
            role: 'user',
            content: text
        })

        setLoading(true)

        const typingEl = appendTyping()
        const assistantBubbleRef = {
            current: null
        }

        let acc = ''
        let lastStageShown = ''

        try {
            const resp = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: text,
                    history
                })
            })

            if (!resp.ok) {
                let data = null
                try {
                    data = await resp.json()
                } catch (_) {
                    data = {
                        detail: 'Response bukan JSON'
                    }
                }
                typingEl.remove()
                appendBubble('assistant', 'Error: ' + (data.detail || 'Gagal memproses permintaan'))
                return
            }

            const reader = resp.body.getReader()
            const decoder = new TextDecoder('utf-8')
            let buffer = ''

            while (true) {
                const {
                    value,
                    done
                } = await reader.read()
                if (done) break

                buffer += decoder.decode(value, {
                    stream: true
                })
                const parts = buffer.split(/\n\n/)
                buffer = parts.pop() || ''

                for (const part of parts) {
                    const events = parseSSEChunk(part)
                    for (const payload of events) {
                        if (!payload || !payload.type) continue

                        if (payload.type === 'meta') {
                            const label = stageLabel(payload.stage)
                            if (label && label !== lastStageShown) {
                                const ab = ensureAssistantBubble(typingEl, assistantBubbleRef)
                                if (!acc.trim()) {
                                    acc = label + '\n'
                                    ab.bubble.textContent = acc
                                }
                                lastStageShown = label
                            }
                            continue
                        }

                        if (payload.type === 'delta') {
                            const ab = ensureAssistantBubble(typingEl, assistantBubbleRef)
                            acc += (payload.delta || '')
                            ab.bubble.textContent = acc
                            chatBox.scrollTop = chatBox.scrollHeight
                            continue
                        }

                        if (payload.type === 'meta_backend') {
                            continue
                        }

                        if (payload.type === 'done') {
                            const finalReply = (payload.reply || acc || '').trim()
                            const ab = ensureAssistantBubble(typingEl, assistantBubbleRef)
                            ab.bubble.textContent = finalReply
                            history.push({
                                role: 'assistant',
                                content: finalReply
                            })
                            return
                        }

                        if (payload.type === 'error') {
                            typingEl.remove()
                            appendBubble('assistant', 'Error: ' + (payload.message || 'Stream error'))
                            return
                        }
                    }
                }
            }

            typingEl.remove()
            if (!assistantBubbleRef.current) {
                appendBubble('assistant', 'Error: stream ended without output')
                return
            }

            const finalReply = acc.trim()
            if (finalReply) history.push({
                role: 'assistant',
                content: finalReply
            })

        } catch (err) {
            typingEl.remove()
            appendBubble('assistant', 'Error: ' + (err?.message || 'Network error'))
        } finally {
            setLoading(false)
            chatInput.focus()
        }
    }

    chatSend.addEventListener('click', sendMessage)
    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') sendMessage()
    })
</script>

<script>
    (() => {
        const fab = document.getElementById('chatFab')
        const overlay = document.getElementById('chatOverlay')
        const modal = document.getElementById('chatModal')
        const closeBtn = document.getElementById('chatClose')

        const open = () => {
            overlay.classList.remove('hidden')
            modal.classList.remove('hidden')
        }

        const close = () => {
            modal.classList.add('hidden')
            overlay.classList.add('hidden')
        }

        fab?.addEventListener('click', open)
        closeBtn?.addEventListener('click', close)

        overlay?.addEventListener('click', (e) => {
            if (e.target === overlay.firstElementChild) close()
        })

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !overlay.classList.contains('hidden')) close()
        })
    })()
</script>

</html>
