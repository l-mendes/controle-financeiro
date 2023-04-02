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
        'route' => 'transactions.index',
        'is_active' => 'transactions.*'
    ],
    [
        'label' => 'Categorias',
        'icon' => 'fa-solid fa-tags',
        'route' => 'categories.index',
        'is_active' => 'categories.*'
    ],
];
