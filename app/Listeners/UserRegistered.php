<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRegistered
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $categories = [
            [
                'name'  =>  'Lazer',
                'type'  =>  'O',
                'color' =>  '#008ae6',
                'sub_categories' => [
                    [
                        'name' => 'Celular',
                        'type' => 'O',
                        'color' => '#008ae6',
                    ],
                    [
                        'name' => 'Lanches',
                        'type' => 'O',
                        'color' => '#008ae6',
                    ],
                    [
                        'name' => 'Doces',
                        'type' => 'O',
                        'color' => '#008ae6',
                    ],
                ]
            ],
            [
                'name' => 'Imprevistos',
                'type' => 'O',
                'color' => '#6600cc',
                'sub_categories' => [
                    [
                        'name' => 'Manutenção Carro',
                        'type' => 'O',
                        'color' => '#6600cc'
                    ]
                ]
            ],
            [
                'name'  =>  'Moradia',
                'type'  =>  'O',
                'color' =>  '#ff3333',
                'sub_categories' => [
                    [
                        'name' => 'Mercado',
                        'type' => 'O',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Energia',
                        'type' => 'O',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Água',
                        'type' => 'O',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Internet',
                        'type' => 'O',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Gás',
                        'type' => 'O',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Condomínio',
                        'type' => 'O',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'IPTU',
                        'type' => 'O',
                        'color' => '#ff3333',
                    ],
                ]
            ],
            [
                'name'  =>  'Transporte',
                'type'  =>  'O',
                'color' =>  '#558000',
                'sub_categories' => [
                    [
                        'name' => 'Combustível',
                        'type' => 'O',
                        'color' => '#558000',
                    ],
                    [
                        'name' => 'Uber',
                        'type' => 'O',
                        'color' => '#558000',
                    ],
                ]
            ],
            [
                'name'  =>  'Salário',
                'type'  =>  'I',
                'color' =>  '#009900',
                'sub_categories' => [
                    [
                        'name' => 'Recebimento de Nota',
                        'type' => 'I',
                        'color' => '#009900',
                    ],
                    [
                        'name' => 'Recebimento Horas Extras',
                        'type' => 'I',
                        'color' => '#009900',
                    ],
                    [
                        'name' => 'Venda Férias',
                        'type' => 'I',
                        'color' => '#009900',
                    ],
                ]
            ]
        ];

        foreach ($categories as $category) {
            $subCategories = $category['sub_categories'];

            /** @var User $user */
            $user = $event->user;

            $category = $user->categories()->create($category);

            $category->subCategories()->createMany($subCategories);
        }
    }
}
