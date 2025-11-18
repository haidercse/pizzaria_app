<!-- sidebar menu area start -->
<style>
    /* Make sidebar full height */
    .sidebar-menu {
        height: 100vh;
        /* Full viewport height */
        overflow: hidden;
        /* Sidebar layout control */
        display: flex;
        flex-direction: column;
    }

    .sidebar-menu .menu-inner {
        flex: 1;
        /* Take remaining height */
        overflow-y: auto;
        /* Scrollable area */
    }

    /* Ensure submenu collapses properly and allows scroll */
    .sidebar-menu .menu-inner nav ul.collapse {
        max-height: none;
        /* No fixed height */
        overflow: visible;
        /* Let parent handle scroll */
    }

    .sidebar-menu .menu-inner::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-menu .menu-inner::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .sidebar-menu .menu-inner::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .super-admin-only>a {
        background: rgba(255, 255, 255, 0.08) !important;
        /* 8% white overlay */
    }

    .super-admin-only ul li a {
        background: rgba(255, 255, 255, 0.05) !important;
        /* submenu slightly lighter */
    }

    .super-admin-only.active>a {
        background: rgba(255, 255, 255, 0.12) !important;
        /* active a bit brighter */
        font-weight: 600;
    }
</style>
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}"><img style="border-radius: 50%; object-fit: cover;"
                    src="{{ asset('admin/assets/images/frankie/pizza_red_only.png') }}" alt="logo"></a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}" aria-expanded="true">
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li
                        class="{{ request()->routeIs('availability.index') || request()->routeIs('availability.create') || request()->routeIs('shift.employee') || request()->routeIs('all.shifts') || request()->routeIs('checkout.create') || request()->routeIs('checkout.index') || request()->routeIs('checkout.monthly_overview') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true">
                            <span>Schedule</span>
                        </a>
                        <ul class="collapse">
                            <li class="{{ request()->routeIs('availability.index') ? 'active' : '' }}">
                                <a href="{{ route('availability.index') }}">Availability Report</a>
                            </li>
                            <li class="{{ request()->routeIs('availability.create') ? 'active' : '' }}">
                                <a href="{{ route('availability.create') }}">Set Availability</a>
                            </li>
                            <li class="{{ request()->routeIs('shift.employee') ? 'active' : '' }}">
                                <a href="{{ route('shift.employee') }}">My Shifts</a>
                            </li>
                            <li class="{{ request()->routeIs('all.shifts') ? 'active' : '' }}">
                                <a href="{{ route('all.shifts') }}">All Shifts</a>
                            </li>
                            {{-- <li class="{{ request()->routeIs('checkout.create') ? 'active' : '' }}">
                                <a href="{{ route('checkout.create') }}">Submit your shift today</a>
                            </li> --}}
                            <li class="{{ request()->routeIs('checkout.index') ? 'active' : '' }}">
                                <a href="{{ route('checkout.index') }}">shift Checkouts </a>
                            </li>
                            @if (Auth::user()->can('checkout.monthly_overview') ||
                                    Auth::user()->hasRole('super admin') ||
                                    Auth::user()->is_superadmin == 1)
                                <li
                                    class=" super-admin-only {{ request()->routeIs('checkout.monthly_overview') ? 'active' : '' }}">
                                    <a href="{{ route('checkout.monthly_overview') }}">Monthly Overview total shift for
                                        all employee</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    @if (Auth::user()->can('shift.show') ||
                            Auth::user()->hasRole('super admin') ||
                            Auth::user()->can('shift-manager.index') ||
                            Auth::user()->is_superadmin == 1)
                        <li
                            class=" super-admin-only {{ request()->routeIs('shift-manager.index') || request()->routeIs('shift.show') ? 'active' : '' }}">
                            <a href="javascript:void(0)" aria-expanded="true">
                                <span>Shift Management</span>
                            </a>
                            <ul class="collapse">
                                <li
                                    class=" super-admin-only{{ request()->routeIs('shift-manager.index') ? 'active' : '' }}">
                                    <a href="{{ route('shift-manager.index') }}">Shift Assign</a>
                                </li>
                                <li class=" super-admin-only{{ request()->routeIs('shift.show') ? 'active' : '' }}">
                                    <a href="{{ route('shift.show') }}">Shift Overview</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <li
                        class="{{ request()->routeIs('tasks.index') ||
                        request()->routeIs('tasks.create') ||
                        request()->routeIs('tasks.edit') ||
                        request()->routeIs('tasks.monthly.matrix') ||
                        request()->routeIs('tasks.daily') ||
                        request()->routeIs('tasks.checklist') ||
                        request()->routeIs('user.tasks.checklist')
                            ? 'active'
                            : '' }}">

                        <a href="javascript:void(0)" aria-expanded="true">
                            <span>Tasks</span>
                        </a>

                        <ul class="collapse">

                            @if (!Auth::user()->hasRole('employee'))
                                <li class=" super-admin-only {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                                    <a href="{{ route('tasks.index') }}">Task List</a>
                                </li>

                                <li class=" super-admin-only {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                                    <a href="{{ route('tasks.create') }}">Task Create</a>
                                </li>

                                <li class=" super-admin-only {{ request()->routeIs('tasks.monthly.matrix') ? 'active' : '' }}">
                                    <a href="{{ route('tasks.monthly.matrix') }}">Task Monthly check (Opening and
                                        Closing)</a>
                                </li>

                                <li class=" super-admin-only {{ request()->routeIs('tasks.daily') ? 'active' : '' }}">
                                    <a href="{{ route('tasks.daily') }}">Daily Task Show and Edit for manager</a>
                                </li>

                                <li class=" super-admin-only {{ request()->routeIs('tasks.checklist') ? 'active' : '' }}">
                                    <a href="{{ route('tasks.checklist') }}">Daily Task checklist full month for
                                        manager</a>
                                </li>
                            @endif

                            <li class="{{ request()->routeIs('user.tasks.checklist') ? 'active' : '' }}">
                                <a href="{{ route('user.tasks.checklist') }}">Daily Task checklist</a>
                            </li>

                        </ul>

                    </li>


                    {{-- task opening and closing --}}
                    <li
                        class="{{ request()->routeIs('tasks.opening.index') || request()->routeIs('tasks.closing.index') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true">
                            <span>Opening && Closing Task</span>
                        </a>
                        <ul class="collapse">
                            <li class="{{ request()->routeIs('tasks.opening.index') ? 'active' : '' }}">
                                <a href="{{ route('tasks.opening.index') }}">Opening Task List</a>
                            </li>
                            <li class="{{ request()->routeIs('tasks.closing.index') ? 'active' : '' }}">
                                <a href="{{ route('tasks.closing.index') }}"> Closing Task List</a>
                        </ul>
                    </li>

                    {{-- <li class="{{ request()->routeIs('dough_making.yeast_salt_list') ? 'active' : '' }}">
                    <a class="{{ request()->routeIs('dough_making.yeast_salt_list') ? 'active' : '' }}"
                        href="{{ route('dough_making.yeast_salt_list') }}" aria-expanded="true">
                        <span>Dough Making table</span>
                    </a>
                </li> --}}
                    <li
                        class="{{ request()->routeIs('dough.index') || request()->routeIs('dough.edit') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true"><span>Dough</span></a>
                        <ul class="collapse">
                            <li class="{{ request()->routeIs('dough.index') ? 'active' : '' }}">
                                <a href="{{ route('dough.index') }}">Dough Making</a>
                            </li>
                            <li class="{{ request()->routeIs('dough.index') ? 'active' : '' }}">
                                <a href="{{ route('dough.index') }}">Dough Table</a>
                            </li>
                        </ul>
                    </li>
                    @if (Auth::user()->can('preps.list') ||
                            Auth::user()->hasRole('super admin') ||
                            Auth::user()->can('preps.create') ||
                            Auth::user()->can('preps.index') ||
                            Auth::user()->is_superadmin == 1)
                        <li
                            class="{{ request()->routeIs('preps.list') || request()->routeIs('preps.index') ? 'active' : '' }}">
                            <a href="javascript:void(0)" aria-expanded="true">
                                <span>Preps</span>
                            </a>
                            <ul class="collapse">
                                <li class=" super-admin-only {{ request()->routeIs('preps.list') ? 'active' : '' }}">
                                    <a href="{{ route('preps.list') }}">All Preps</a>
                                </li>
                                <li class="{{ request()->routeIs('preps.index') ? 'active' : '' }}">
                                    <a href="{{ route('preps.index') }}">Preps List</a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->can('roles.index') ||
                            Auth::user()->hasRole('super admin') ||
                            Auth::user()->can('roles.create') ||
                            Auth::user()->can('permissions.index') ||
                            Auth::user()->is_superadmin == 1)
                        <li
                            class=" super-admin-only{{ request()->routeIs('permissions.index') || request()->routeIs('roles.index') || request()->routeIs('roles.create') || request()->routeIs('roles.edit') ? 'active' : '' }}">
                            <a href="javascript:void(0)" aria-expanded="true">
                                <span>Role & Permission</span>
                            </a>
                            <ul class="collapse">
                                <li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                    <a href="{{ route('roles.index') }}">Role</a>
                                </li>
                                <li class="{{ request()->routeIs('roles.create') ? 'active' : '' }}">
                                    <a href="{{ route('roles.create') }}">Role Create</a>
                                </li>
                                <li class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                                    <a href="{{ route('permissions.index') }}">Permission</a>
                                    <!-- FIXED: Empty route removed -->
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (Auth::user()->can('users.index') ||
                            Auth::user()->can('day_tasks.index') ||
                            Auth::user()->can('holidays.index') ||
                            Auth::user()->can('events.index') ||
                            Auth::user()->hasRole('super admin') ||
                            Auth::user()->is_superadmin == 1)
                        <li
                            class="super-admin-only {{ request()->routeIs('users.create') || request()->routeIs('users.index') || request()->routeIs('day_tasks.index') || request()->routeIs('holidays.index') ? 'active' : '' }}">
                            <a href="javascript:void(0)" aria-expanded="true">
                                <span>Staff and Events</span>
                            </a>
                            <ul class="collapse">
                                @if (Auth::user()->can('users.index') || Auth::user()->hasRole('super admin') || Auth::user()->is_superadmin == 1)
                                    <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <a href="{{ route('users.index') }}">Employee List</a>
                                    </li>
                                @endif
                                @if (Auth::user()->can('day_tasks.index') || Auth::user()->hasRole('super admin') || Auth::user()->is_superadmin == 1)
                                    <li class="{{ request()->routeIs('day_tasks.index') ? 'active' : '' }}">
                                        <a href="{{ route('day_tasks.index') }}">Day Tasks</a>
                                    </li>
                                @endif
                                @if (Auth::user()->can('holidays.index') || Auth::user()->hasRole('super admin') || Auth::user()->is_superadmin == 1)
                                    <li class="{{ request()->routeIs('holidays.index') ? 'active' : '' }}">
                                        <a href="{{ route('holidays.index') }}">Holiday</a>
                                    </li>
                                @endif

                                @if (Auth::user()->can('events.index') || Auth::user()->hasRole('super admin') || Auth::user()->is_superadmin == 1)
                                    <li class="{{ request()->routeIs('events.index') ? 'active' : '' }}">
                                        <a href="{{ route('events.index') }}">Events/Catering</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                        <a class="{{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}"
                            aria-expanded="true">
                            <span>Profile</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('logout') ? 'active' : '' }}">
                        <a class="{{ request()->routeIs('logout') ? 'active' : '' }}" href="{{ route('logout') }}"
                            aria-expanded="true">
                            <span>Logout</span>
                        </a>
                    </li>
                    {{-- Remove this dummy section in production --}}
                    {{-- <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-align-left"></i> <span>Multi level menu</span></a>
                        <ul class="collapse">
                            <li><a href="#">Item level (1)</a></li>
                            <li><a href="#">Item level (1)</a></li>
                            <li><a href="#" aria-expanded="true">Item level (1)</a>
                                <ul class="collapse">
                                    <li><a href="#">Item level (2)</a></li>
                                    <li><a href="#">Item level (2)</a></li>
                                    <li><a href="#">Item level (2)</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Item level (1)</a></li>
                        </ul>
                    </li> --}}
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->
