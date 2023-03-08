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
                        'color' => '#008ae6',
                    ],
                    [
                        'name' => 'Lanches',
                        'color' => '#008ae6',
                    ],
                    [
                        'name' => 'Doces',
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
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Energia',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Água',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Internet',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Gás',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'Condomínio',
                        'color' => '#ff3333',
                    ],
                    [
                        'name' => 'IPTU',
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
                        'color' => '#558000',
                    ],
                    [
                        'name' => 'Uber',
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
                        'color' => '#009900',
                    ],
                    [
                        'name' => 'Recebimento Horas Extras',
                        'color' => '#009900',
                    ],
                    [
                        'name' => 'Venda Férias',
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
