<!-- sidebar menu area start -->
<style>
    .sidebar-menu { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
    .sidebar-menu .menu-inner { flex: 1; overflow-y: auto; }
    .sidebar-menu .menu-inner nav ul.collapse { max-height: none; overflow: visible; }
    .sidebar-menu .menu-inner::-webkit-scrollbar { width: 6px; }
    .sidebar-menu .menu-inner::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
    .sidebar-menu .menu-inner::-webkit-scrollbar-thumb:hover { background: #555; }
</style>

<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <img style="border-radius: 50%; object-fit: cover;"
                     src="{{ asset('admin/assets/images/frankie/pizza_red_only.png') }}" alt="logo">
            </a>
        </div>
    </div>

    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    @foreach(config('menu') as $item)
                        @php
                            $isActive = isset($item['submenu']) 
                                ? collect($item['submenu'])->pluck('route')->contains(fn($r) => request()->routeIs($r)) 
                                : request()->routeIs($item['route'] ?? '');
                        @endphp
                        <li class="{{ $isActive ? 'active' : '' }}">
                            @if(isset($item['submenu']))
                                <a href="javascript:void(0)" aria-expanded="true"><span>{{ $item['title'] }}</span></a>
                                <ul class="collapse {{ $isActive ? 'show' : '' }}">
                                    @foreach($item['submenu'] as $sub)
                                        <li class="{{ request()->routeIs($sub['route'] ?? '') ? 'active' : '' }}">
                                            <a href="{{ route($sub['route']) }}">{{ $sub['title'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a href="{{ route($item['route']) ?? '#' }}"><span>{{ $item['title'] }}</span></a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->
