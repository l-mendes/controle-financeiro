<?php

return [
    [
        'label' => 'Dashboard',
        'icon' => 'fa-solid fa-gauge',
        'route' => 'dashboard',
        'is_active' => 'dashboard*'
    ],
    [
        'label' => 'Transações',
        'icon' => 'fa-solid fa-arrow-right-arrow-left',
        'route' => 'transactions',
        'is_active' => 'transactions*'
    ],
    [
        'label' => 'Entradas',
        'icon' => 'fa-regular fa-circle-up',
        'route' => 'inbound',
        'is_active' => 'inbound*'
    ],
    [
        'label' => 'Saídas',
        'icon' => 'fa-regular fa-circle-down',
        'route' => 'outbound',
        'is_active' => 'outbound*'
    ],
    [
        'label' => 'Categorias',
        'icon' => 'fa-solid fa-tags',
        'route' => 'categories.index',
        'is_active' => 'categories.*'
    ],
];
