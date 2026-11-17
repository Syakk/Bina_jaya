@php
    $menus = [
        [
            'title' => 'Menu Utama',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'route' => route('dashboard'),
                    'active' => request()->routeIs('dashboard'),
                    'icon' => '<svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>',
                ],
                [
                    'name' => 'Manajemen Stok',
                    'route' => route('barang.index'),
                    'active' => request()->routeIs('barang.*'),
                    'icon' => '<svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>',
                ],
                [
                    'name' => 'Transaksi Keluar',
                    'route' => route('transaksi.index'),
                    'active' => request()->routeIs('transaksi.*'),
                    'icon' => '<svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>',
                ],
            ],
        ],
    ];
@endphp

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 start-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out max-xl:duration-200 max-xl:w-[290px] z-50 max-xl:z-101 ltr:border-r rtl:border-l border-gray-200 w-[90px] [.sidebar-expanded_&]:min-w-[290px]"
    :class="{
        'translate-x-0': $store.sidebar.isMobileOpen,
        'max-xl:-translate-x-full max-xl:rtl:translate-x-full': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded)$store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    <!-- Logo / Nama Aplikasi -->
    <div class="py-7 flex items-center gap-3" :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'justify-center' : 'justify-start'">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-brand-500 text-white font-bold text-lg">
                BJ
            </div>
            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered \vert{}\vert{}$store.sidebar.isMobileOpen"
                class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                Bina Jaya
            </span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-6">
                @foreach ($menus as $group)
                    <div>
                        <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider flex leading-[20px] text-gray-400"
                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'lg:justify-center' : 'justify-start'">
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered \vert{}\vert{}$store.sidebar.isMobileOpen">
                                {{ $group['title'] }}
                            </span>
                            <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                                •••
                            </span>
                        </h2>

                        <ul class="flex flex-col gap-1.5">
                            @foreach ($group['items'] as $item)
                                <li>
                                    <a href="{{ $item['route'] }}"
                                        class="menu-item group {{ $item['active'] ? 'menu-item-active' : 'menu-item-inactive' }}"
                                        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
                                        <span class="{{ $item['active'] ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                                            {!! $item['icon'] !!}
                                        </span>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered \vert{}\vert{}$store.sidebar.isMobileOpen"
                                            class="menu-item-text">
                                            {{ $item['name'] }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </nav>
    </div>
</aside>