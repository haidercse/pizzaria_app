<?php

return [
    [
        'title' => 'Dashboard',
        'route' => 'admin.dashboard',
        'icon'  => null,
        'permission' => null,
    ],
    [
        'title' => 'Dough',
        'permission' => 'manage-dough',
        'submenu' => [
            [
                'title' => 'Dough List',
                'route' => 'dough.index',
                'permission' => 'manage-dough',
            ],
            [
                'title' => 'Add Dough Litter',
                'route' => 'dough.create',
                'permission' => 'manage-dough',
            ],
        ],
    ],
    [
        'title' => 'Settings',
        'permission' => 'manage-settings',
        'submenu' => [
            [
                'title' => 'Employee Registration',
                'route' => 'users.create',
                'permission' => 'manage-employees',
            ],
            [
                'title' => 'Employee List',
                'route' => 'users.index',
                'permission' => 'manage-employees',
            ],
            [
                'title' => 'Day Tasks',
                'route' => 'day_tasks.index',
                'permission' => 'manage-tasks',
            ],
            [
                'title' => 'Holiday',
                'route' => 'holidays.index',
                'permission' => 'manage-holidays',
            ],
        ],
    ],
    [
        'title' => 'Employee Shift Setup',
        'permission' => 'manage-shifts',
        'submenu' => [
            [
                'title' => 'Shift Availability',
                'route' => 'availability.index',
                'permission' => 'view-shifts',
            ],
            [
                'title' => 'Set Shift Availability',
                'route' => 'availability.create',
                'permission' => 'manage-shifts',
            ],
            [
                'title' => 'My Shifts',
                'route' => 'shift.employee',
                'permission' => 'view-shifts',
            ],
            [
                'title' => 'All Shifts',
                'route' => 'all.shifts',
                'permission' => 'view-shifts',
            ],
            [
                'title' => 'Submit your shift today',
                'route' => 'checkout.create',
                'permission' => 'manage-shifts',
            ],
            [
                'title' => 'All Checkouts',
                'route' => 'checkout.index',
                'permission' => 'view-checkouts',
            ],
            [
                'title' => 'Monthly Overview',
                'route' => 'checkout.monthly_overview',
                'permission' => 'view-checkouts',
            ],
        ],
    ],
    [
        'title' => 'Shift Management',
        'permission' => 'manage-shifts',
        'submenu' => [
            [
                'title' => 'Shift Assignment',
                'route' => 'shift-manager.index',
                'permission' => 'manage-shifts',
            ],
            [
                'title' => 'Shift Overview',
                'route' => 'shift.show',
                'permission' => 'manage-shifts',
            ],
        ],
    ],
    [
        'title' => 'Profile',
        'route' => 'profile',
        'permission' => null,
    ],
    [
        'title' => 'Tasks',
        'permission' => 'manage-tasks',
        'submenu' => [
            ['title' => 'Task List', 'route' => 'tasks.index', 'permission' => 'manage-tasks'],
            ['title' => 'Task Opening', 'route' => 'tasks.opening.index', 'permission' => 'manage-tasks'],
            ['title' => 'Task Closing', 'route' => 'tasks.closing.index', 'permission' => 'manage-tasks'],
            ['title' => 'Task Create', 'route' => 'tasks.create', 'permission' => 'manage-tasks'],
            ['title' => 'Task Monthly Matrix', 'route' => 'tasks.monthly.matrix', 'permission' => 'manage-tasks'],
            ['title' => 'Daily Task Show and Edit', 'route' => 'tasks.daily', 'permission' => 'manage-tasks'],
            ['title' => 'Daily Task Checklist (Full Month)', 'route' => 'tasks.checklist', 'permission' => 'manage-tasks'],
            ['title' => 'Daily Task Checklist (Employee)', 'route' => 'user.tasks.checklist', 'permission' => 'manage-tasks'],
        ],
    ],
    [
        'title' => 'Preps',
        'permission' => 'none',
        'submenu' => [
            ['title' => 'Prep List', 'route' => 'preps.index', 'permission' => 'none'],
            ['title' => 'Show All Preps', 'route' => 'preps.list', 'permission' => 'none'],
            ['title' => 'Create New Prep', 'route' => 'preps.create', 'permission' => 'none'],

        ],
    ],
    [
        'title' => 'Logout',
        'route' => 'logout',
        'permission' => null,
    ],
];
