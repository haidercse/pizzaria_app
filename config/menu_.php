 <?php

return [
    [
        'title' => 'Dashboard',
        'route' => 'admin.dashboard',
        'icon'  => null,
        'permission' => 'admin.dashboard',
    ],
    [
        'title' => 'Dough',
        'permission' => '',
        'submenu' => [
            [
                'title' => 'Dough List',
                'route' => 'dough.index',
                'permission' => '',
            ],

        ],
    ],
    [
        'title' => 'Settings',
        'permission' => '',
        'submenu' => [

            [
                'title' => 'Employee List',
                'route' => 'users.index',
                'permission' => 'none',
            ],
            [
                'title' => 'Day Tasks',
                'route' => 'day_tasks.index',
                'permission' => 'none',
            ],
            [
                'title' => 'Holiday',
                'route' => 'holidays.index',
                'permission' => 'holidays.index',
            ],
            [
                'title' => 'Event',
                'route' => 'events.index',
                'permission' => 'events.index',
            ],
        ],
    ],
    [
        'title' => 'Employee Shift Setup',
        'permission' => '',
        'submenu' => [
            [
                'title' => 'Shift Availability',
                'route' => 'availability.index',
                'permission' => '',
            ],
            [
                'title' => 'Set Shift Availability',
                'route' => 'availability.create',
                'permission' => '',
            ],
            [
                'title' => 'My Shifts',
                'route' => 'shift.employee',
                'permission' => '',
            ],
            [
                'title' => 'All Shifts',
                'route' => 'all.shifts',
                'permission' => '',
            ],
            [
                'title' => 'Submit your shift today',
                'route' => 'checkout.create',
                'permission' => '',
            ],
            [
                'title' => 'All Checkouts',
                'route' => 'checkout.index',
                'permission' => '',
            ],
            [
                'title' => 'Monthly Overview',
                'route' => 'checkout.monthly_overview',
                'permission' => '',
            ],
        ],
    ],
    [
        'title' => 'Shift Management',
        'permission' => '',
        'submenu' => [
            [
                'title' => 'Shift Assignment',
                'route' => 'shift-manager.index',
                'permission' => '',
            ],
            [
                'title' => 'Shift Overview',
                'route' => 'shift.show',
                'permission' => '',
            ],
        ],
    ],
    [
        'title' => 'Profile',
        'route' => 'profile',
        'permission' => '',
    ],
    [
        'title' => 'Tasks',
        'permission' => '',
        'submenu' => [
            ['title' => 'Task List', 'route' => 'tasks.index', 'permission' => ''],
            ['title' => 'Task Opening', 'route' => 'tasks.opening.index', 'permission' => ''],
            ['title' => 'Task Closing', 'route' => 'tasks.closing.index', 'permission' => ''],
            ['title' => 'Task Create', 'route' => 'tasks.create', 'permission' => ''],
            ['title' => 'Task Monthly Matrix', 'route' => 'tasks.monthly.matrix', 'permission' => ''],
            ['title' => 'Daily Task Show and Edit', 'route' => 'tasks.daily', 'permission' => ''],
            ['title' => 'Daily Task Checklist (Full Month)', 'route' => 'tasks.checklist', 'permission' => ''],
            ['title' => 'Daily Task Checklist (Employee)', 'route' => 'user.tasks.checklist', 'permission' => ''],
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
        'title' => 'Dough Making Tables',
        'route' => 'dough_making.yeast_salt_list',
        'permission' => '',
    ],
    [
        'title' => 'Roles && Permission',
        'permission' => 'none',
        'submenu' => [
            [
                'title' => 'Roles',
                'route' => 'roles.index',
                'permission' => 'none',
            ],
            [
                'title' => 'Permission',
                'route' => 'permissions.index',
                'permission' => 'none',
            ],
            
        ],
    ],
    [
        'title' => 'Logout',
        'route' => 'logout',
        'permission' => null,
    ],
];
