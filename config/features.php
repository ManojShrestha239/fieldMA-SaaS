<?php

return [
    'map' => [
        'notices' => [
            'label' => 'Notices',
            'details' => 'Notice Board & Alerts',
            // 'sub_features' => []
        ],
        'dealer_orders' => [
            'label' => 'Dealer Orders',
            'details' => 'Dealer Order Management',
            'sub_features' => ['new_order', 'order_history', 'saved_orders'],
        ],
        'retailer_orders' => [
            'label' => 'Retailer Order',
            'details' => 'Retailer Order Management',
            'sub_features' => ['new_order', 'order_history', 'saved_orders'],
        ],
        'collections' => [
            'label' => 'Payments/Collections',
            'details' => 'Payments/Collections Management',
            'sub_features' => ['new_collection', 'collection_history'],
        ],
        'schedules' => [
            'label' => 'Schedules Visits',
            'details' => 'Schedules Visits Management',
            // 'sub_features' => []
        ],
        'chats' => [
            'label' => 'Chats',
            'details' => 'Chats Management',
            // 'sub_features' => []
        ],
        'complaints' => [
            'label' => 'Complaints',
            'details' => 'Complaints Management',
            // 'sub_features' => []
        ],
        'visits' => [
            'label' => 'Visits',
            'details' => 'Visits Management',
            'sub_features' => ['online_visit', 'offline_visit', 'unlisted_visit'],
        ],
        'reminder_alert' => [
            'label' => 'Reminder & Alerts',
            'details' => 'Reminder & Alerts Management',
            // 'sub_features' => []
        ],
        'position' => [
            'label' => 'Position',
            'details' => 'Position Management',
            'sub_features' => ['dealer_position', 'dealer_reposition'],
        ],
        'inventory' => [
            'label' => 'Inventory',
            'details' => 'Inventory Management',
            // 'sub_features' => []
        ],
        'daily_reports' => [
            'label' => 'Daily Reports/TADA',
            'details' => 'Daily Reports/TADA Management',
            // 'sub_features' => []
        ],
        'tracking_toggle' => [
            'label' => 'Toogle Tracking',
            'details' => 'Toogle Tracking Management',
            // 'sub_features' => []
        ],
        'team_hierarchy' => [
            'label' => 'Team Hierarchy',
            'details' => 'Team Hierarchy Management',
            // 'sub_features' => []
        ],
        'site_registration' => [
            'label' => 'Site Registration',
            'details' => 'Site Registration Management',
            // 'sub_features' => []
        ],
    ],

    // 'permissions_map' => [
    //     'notices' => ['notice'],
    //     'dealer_orders' => ['order'],
    //     'retailer_orders' => ['order'],
    //     'collections' => ['collection'],
    //     'schedules' => ['schedule'],
    //     'chats' => ['chat'],
    //     'complaints' => ['complaint'],
    //     'visits' => ['reports'],
    //     'reminder_alert' => [],
    //     'position' => ['dealer'],
    //     'inventory' => ['product', 'productinventory', 'rawmaterial'],
    //     'daily_reports' => ['reports'],
    //     'tracking_toggle' => [],
    //     'team_hierarchy' => [],
    //     'site_registration' => ['site_registration'],
    // ],

    // 'core_permissions' => [
    //     'user' => [
    //         'label' => 'User Management',
    //         'details' => 'Admins, Managers & Field Staff',
    //     ],
    //     'dealer' => [
    //         'label' => 'Dealer Setup',
    //         'details' => 'Dealer & Subdealer Master Data',
    //     ],
    //     'area' => [
    //         'label' => 'Area Setup',
    //         'details' => 'Territories, Areas & Subareas',
    //     ],
    //     'bank' => [
    //         'label' => 'Bank Accounts',
    //         'details' => 'Company Bank Accounts',
    //     ],
    //     'upload-due' => [
    //         'label' => 'Upload Dues',
    //         'details' => 'Upload Outstanding Customer Dues',
    //     ],
    //     'distance' => [
    //         'label' => 'Distance Limit',
    //         'details' => 'Location & Distance Geofencing Limits',
    //     ],
    //     'auto-checkout' => [
    //         'label' => 'Auto-Checkout Time',
    //         'details' => 'Configure Daily Auto-Checkout Times',
    //     ],
    //     'denomination' => [
    //         'label' => 'Denominations',
    //         'details' => 'Currency Denominations Configuration',
    //     ],
    //     'purpose' => [
    //         'label' => 'Visit Purposes',
    //         'details' => 'Field Agent Visit Purpose Types',
    //     ],
    //     'role' => [
    //         'label' => 'Role Management',
    //         'details' => 'Configure Staff Roles & Permissions',
    //     ],
    //     'privacy-policy' => [
    //         'label' => 'Privacy Policy',
    //         'details' => 'Company & App Privacy Policies',
    //     ],
    // ],
];
