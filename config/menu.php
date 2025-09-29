<?php

// config/menu.php
return [
    /**
     * =================================================================
     * MENU UNTUK SEMUA ROLE (KARYAWAN & ADMIN)
     * =================================================================
     */
    [
        'title' => 'Dashboard',
        'icon'  => 'fa-solid fa-house',
        'route' => 'dashboard',
        'role'  => ['admin', 'user'], // Bisa dilihat admin dan user
    ],
    [
        'title' => 'Absensi',
        'icon'  => 'fa-solid fa-fingerprint',
        'route' => 'attendance.index',
        'role'  => ['admin', 'user'],
    ],
    [
        'title' => 'Cuti & Izin',
        'icon'  => 'fa-solid fa-calendar-day',
        'route' => 'leaves.index',
        'role'  => ['admin', 'user'],
    ],
    [
        'title' => 'Lembur',
        'icon'  => 'fa-solid fa-business-time',
        'route' => 'overtime.index',
        'role'  => ['admin', 'user'],
    ],
    [
        'title' => 'Laporan',
        'icon'  => 'fa-solid fa-chart-line',
        'route' => 'reports.attendances',
        'role'  => ['admin', 'user'],
    ],
    
    /**
     * =================================================================
     * GRUP MENU KHUSUS ADMIN
     * =================================================================
     */
    [
        'title' => 'Manajemen',
        'icon'  => 'fa-solid fa-cogs',
        'role'  => ['admin'], // Hanya bisa dilihat oleh admin
        'submenu' => [
            [
                'title' => 'Karyawan',
                'icon'  => 'fa-solid fa-users',
                'route' => 'admin.users.index',
            ],
            [
                'title' => 'Persetujuan Cuti',
                'icon'  => 'fa-solid fa-check-to-slot',
                'route' => 'admin.leaves.index',
            ],
            // ▼▼▼ INI MENU BARU YANG DITAMBAHKAN ▼▼▼
            [
                'title' => 'Persetujuan Lembur',
                'icon'  => 'fa-solid fa-user-clock',
                'route' => 'admin.overtime-approvals.index',
            ],
            // ▲▲▲ ------------------------------ ▲▲▲
            [
                'title' => 'Hari Libur',
                'icon'  => 'fa-solid fa-calendar-check',
                'route' => 'admin.holidays.index',
            ],
            [
                'title' => 'Event Lembur',
                'icon'  => 'fa-solid fa-calendar-plus',
                'route' => 'admin.overtime-events.index',
            ],
            [
                'title' => 'Jadwal Kerja',
                'icon'  => 'fa-solid fa-clock',
                'route' => 'admin.shifts.index',
            ],
            [
                'title' => 'Lokasi (Geofence)',
                'icon'  => 'fa-solid fa-map-marked-alt',
                'route' => 'admin.locations.index',
            ],
        ]
    ],
];