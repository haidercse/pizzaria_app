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
</style>

<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="index.html"><img style="border-radius: 50%; object-fit: cover;"
                    src="{{ asset('admin/assets/images/frankie/pizza_red_only.png') }}" alt="logo"></a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"> <a
                            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}" aria-expanded="true"><span>Dashboard</span></a></li>

                    <li
                        class="{{ request()->routeIs('dough.index') || request()->routeIs('dough.create') || request()->routeIs('dough.edit') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true"><span>Dough</span></a>
                        <ul class="collapse">
                            <li class="{{ request()->routeIs('dough.index') ? 'active' : '' }}"><a
                                    href="{{ route('dough.index') }}">Dough List</a></li>
                            <li class="{{ request()->routeIs('dough.create') ? 'active' : '' }}"><a
                                    href="{{ route('dough.create') }}">Add Dough Litter</a></li>

                        </ul>
                    </li>


                    {{-- <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-slice"></i><span>icons</span></a>
                        <ul class="collapse">
                            <li><a href="fontawesome.html">fontawesome icons</a></li>
                            <li><a href="themify.html">themify icons</a></li>
                        </ul>
                    </li> --}}
                    {{-- <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-table"></i>
                            <span>Tables</span></a>
                        <ul class="collapse">
                            <li><a href="table-basic.html">basic table</a></li>
                            <li><a href="table-layout.html">table layout</a></li>
                            <li><a href="datatable.html">datatable</a></li>
                        </ul>
                    </li> --}}

                    <li
                        class="{{ request()->routeIs('users.create') || request()->routeIs('users.index') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true">
                            <span>Settings</span></a>
                        <ul class="collapse">
                            <li class="{{ request()->routeIs('users.create') ? 'active' : '' }}"><a
                                    href="{{ route('users.create') }}">Employee Registration</a></li>
                            <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}"><a
                                    href="{{ route('users.index') }}">Employee List</a></li>
                        </ul>
                    </li>
                    <li
                        class="{{ request()->routeIs('availability.index') || request()->routeIs('availability.create') || request()->routeIs('shift.employee') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true">
                            <span>Employee Shift setup</span></a>
                        <ul class="collapse">
                            <li class="{{ request()->routeIs('availability.index') ? 'active' : '' }}"><a
                                    href="{{ route('availability.index') }}">Shift Availability</a></li>
                            <li class="{{ request()->routeIs('availability.create') ? 'active' : '' }}"><a
                                    href="{{ route('availability.create') }}">Set Shift Availability</a></li>
                            <li class="{{ request()->routeIs('shift.employee') ? 'active' : '' }}"><a
                                    href="{{ route('shift.employee') }}">My Shifts</a></li>

                        </ul>
                    </li>
                    <li
                        class="{{ request()->routeIs('shift-manager.index') || request()->routeIs('shift.show') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true">
                            <span>Shift Management</span></a>
                        <ul class="collapse">
                            <li class="{{ request()->routeIs('shift-manager.index') ? 'active' : '' }}"><a
                                    href="{{ route('shift-manager.index') }}">Shift Assignment</a></li>
                            <li class="{{ request()->routeIs('shift.show') ? 'active' : '' }}"><a
                                    href="{{ route('shift.show') }}">Shift Overview</a></li>
                            
                            

                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                        <a class="{{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}"
                            aria-expanded="true">
                            <span>Profile</span></a>
                    </li>
                    <li
                        class="{{ request()->routeIs('tasks.index') || request()->routeIs('tasks.create') || request()->routeIs('tasks.edit') || request()->routeIs('tasks.opening.index') || request()->routeIs('tasks.closing.index') || request()->routeIs('tasks.monthly.matrix') || request()->routeIs('tasks.daily') || request()->routeIs('tasks.checklist') || request()->routeIs('user.tasks.checklist') ? 'active' : '' }}">
                        <a href="javascript:void(0)" aria-expanded="true"><span>Tasks</span></a>
                        <ul class="collapse">

                            <li class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}"><a
                                    href="{{ route('tasks.index') }}">Task List</a></li>

                            <li class="{{ request()->routeIs('tasks.opening.index') ? 'active' : '' }}"><a
                                    href="{{ route('tasks.opening.index') }}">Task opening</a></li>

                            <li class="{{ request()->routeIs('tasks.closing.index') ? 'active' : '' }}"><a
                                    href="{{ route('tasks.closing.index') }}">Task Closing</a></li>

                            <li class="{{ request()->routeIs('tasks.create') ? 'active' : '' }}"><a
                                    href="{{ route('tasks.create') }}"> Task Create</a></li>

                            <li class="{{ request()->routeIs('tasks.monthly.matrix') ? 'active' : '' }}"><a
                                    href="{{ route('tasks.monthly.matrix') }}"> Task Monthly Matrix</a></li>
                            <li class="{{ request()->routeIs('tasks.daily') ? 'active' : '' }}"><a
                                    href="{{ route('tasks.daily') }}"> Daily Task Show and Edit </a></li>
                            <li class="{{ request()->routeIs('tasks.checklist') ? 'active' : '' }}"><a
                                    href="{{ route('tasks.checklist') }}"> Daily Task checklist for full month </a>
                            </li>
                            <li class="{{ request()->routeIs('user.tasks.checklist') ? 'active' : '' }}"><a
                                    href="{{ route('user.tasks.checklist') }}"> Daily Task checklist for Employee </a>
                            </li>
                        </ul>
                    </li>




                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-align-left"></i> <span>Multi
                                level menu</span></a>
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
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->
