<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'fa fa-home',
        'route' => 'admin.dashboard',
        'permission' => null,
    ],

    [
        'title' => 'Dough',
        'icon' => 'fa fa-bread-slice',
        'permission' => 'dough.index',
        'submenu' => [
            ['title' => 'Dough List', 'route' => 'dough.index', 'permission' => 'dough.index'],
        ],
    ],

    [
        'title' => 'Settings',
        'icon' => 'fa fa-cog',
        'submenu' => [
            ['title' => 'Employee List', 'route' => 'users.index', 'permission' => 'users.index'],
            ['title' => 'Day Tasks', 'route' => 'day_tasks.index', 'permission' => 'day_tasks.index'],
            ['title' => 'Holiday', 'route' => 'holidays.index', 'permission' => 'holidays.index'],
            ['title' => 'Events', 'route' => 'events.index', 'permission' => 'events.index'],
        ],
    ],

    [
        'title' => 'Employee Shift Setup',
        'icon' => 'fa fa-clock',
        'submenu' => [
            ['title' => 'Shift Availability', 'route' => 'availability.index', 'permission' => 'availability.index'],
            ['title' => 'Set Shift Availability', 'route' => 'availability.create', 'permission' => 'availability.create'],
            ['title' => 'My Shifts', 'route' => 'shift.employee', 'permission' => 'shift.employee'],
            ['title' => 'All Shifts', 'route' => 'all.shifts', 'permission' => 'all.shifts'],
            ['title' => 'Submit Your Shift Today', 'route' => 'checkout.create', 'permission' => 'checkout.create'],
            ['title' => 'All Checkouts', 'route' => 'checkout.index', 'permission' => 'checkout.index'],
            ['title' => 'Monthly Overview', 'route' => 'checkout.monthly_overview', 'permission' => 'checkout.monthly_overview'],
        ],
    ],

    [
        'title' => 'Shift Management',
        'icon' => 'fa fa-calendar-check',
        'submenu' => [
            ['title' => 'Shift Assign', 'route' => 'shift-manager.index', 'permission' => 'shift-manager.index'],
            ['title' => 'Shift Overview', 'route' => 'shift.show', 'permission' => 'shift.show'],
        ],
    ],

    [
        'title' => 'Profile',
        'icon' => 'fa fa-user',
        'route' => 'profile',
        'permission' => null,
    ],

    [
        'title' => 'Tasks',
        'icon' => 'fa fa-tasks',
        'submenu' => [
            ['title' => 'Task List', 'route' => 'tasks.index', 'permission' => 'tasks.index'],
            ['title' => 'Task Create', 'route' => 'tasks.create', 'permission' => 'tasks.create'],
            ['title' => 'Task Monthly Check (Opening & Closing)', 'route' => 'tasks.monthly.matrix', 'permission' => 'tasks.monthly.matrix'],
            ['title' => 'Daily Task Show/Edit (Manager)', 'route' => 'tasks.daily', 'permission' => 'tasks.daily'],
            ['title' => 'Daily Task Checklist (Manager)', 'route' => 'tasks.checklist', 'permission' => 'tasks.checklist'],
            ['title' => 'Daily Task Checklist (Employee)', 'route' => 'user.tasks.checklist', 'permission' => 'user.tasks.checklist'],
        ],
    ],

    [
        'title' => 'Opening & Closing Task',
        'icon' => 'fa fa-door-open',
        'submenu' => [
            ['title' => 'Opening Task List', 'route' => 'tasks.opening.index', 'permission' => 'tasks.opening.index'],
            ['title' => 'Closing Task List', 'route' => 'tasks.closing.index', 'permission' => 'tasks.closing.index'],
        ],
    ],

    [
        'title' => 'Role & Permission',
        'icon' => 'fa fa-lock',
        'submenu' => [
            ['title' => 'Role', 'route' => 'roles.index', 'permission' => 'roles.index'],
            ['title' => 'Role Create', 'route' => 'roles.create', 'permission' => 'roles.create'],
            ['title' => 'Permission', 'route' => 'permissions.index', 'permission' => 'permissions.index'],
        ],
    ],

    [
        'title' => 'Logout',
        'icon' => 'fa fa-sign-out-alt',
        'route' => 'logout',
        'permission' => null,
    ],
];
