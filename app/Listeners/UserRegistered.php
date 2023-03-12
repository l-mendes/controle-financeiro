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
        /** @var User $user */
        $user = $event->user;

        $categories = [
            [
                'name'  =>  'Lazer',
                'type'  =>  'O',
                'color' =>  '#008ae6',
                'sub_categories' => [
                    [
                        'name' => 'Celular',
                        'color' => '#008ae6',
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Lanches',
                        'color' => '#008ae6',
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Doces',
                        'color' => '#008ae6',
                        'user_id' => $user->id,
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
                        'color' => '#6600cc',
                        'user_id' => $user->id,
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
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Energia',
                        'color' => '#ff3333',
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Água',
                        'color' => '#ff3333',
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Internet',
                        'color' => '#ff3333',
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Gás',
                        'color' => '#ff3333',
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Condomínio',
                        'color' => '#ff3333',
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'IPTU',
                        'color' => '#ff3333',
                        'user_id' => $user->id,
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
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Uber',
                        'color' => '#558000',
                        'user_id' => $user->id,
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
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Recebimento Horas Extras',
                        'color' => '#009900',
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => 'Venda Férias',
                        'color' => '#009900',
                        'user_id' => $user->id,
                    ],
                ]
            ]
        ];

        foreach ($categories as $category) {
            $subCategories = $category['sub_categories'];

            $category = $user->categories()->create($category);

            $category->subCategories()->createMany($subCategories);
        }
    }
}
