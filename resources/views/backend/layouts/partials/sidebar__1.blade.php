<ul class="metismenu" id="menu">
    @foreach (config('sidebar') as $menu)
        @php
            $hasSub = isset($menu['submenu']);
            $isActive = $hasSub
                ? collect($menu['submenu'])->pluck('route')->contains(fn($r) => request()->routeIs($r))
                : request()->routeIs($menu['route'] ?? '');
        @endphp

        @if (is_null($menu['permissions']) || Auth::user()->can($menu['permissions']) || Auth::user()->hasRole('super admin'))
            <li class="{{ $isActive ? 'active' : '' }}">
                @if ($hasSub)
                    <a href="javascript:void(0)" aria-expanded="true">
                        @if (!empty($menu['icon']))
                            <i class="{{ $menu['icon'] }}"></i>
                        @endif
                        <span>{{ $menu['title'] }}</span>
                    </a>
                    <ul class="collapse">
                        @foreach ($menu['submenu'] as $sub)
                            @if (is_null($sub['permission']) || Auth::user()->can($sub['permission']) || Auth::user()->hasRole('super admin'))
                                <li class="{{ request()->routeIs($sub['route']) ? 'active' : '' }}">
                                    <a href="{{ route($sub['route']) }}">{{ $sub['title'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <a href="{{ route($menu['route']) }}" class="{{ $isActive ? 'active' : '' }}">
                        @if (!empty($menu['icon']))
                            <i class="{{ $menu['icon'] }}"></i>
                        @endif
                        <span>{{ $menu['title'] }}</span>
                    </a>
                @endif
            </li>
        @endif
    @endforeach
</ul>
