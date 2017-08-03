<?php

use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\UserAuthority;

return [

    'user_authority' => [
        'implementation_class'   => UserAuthority::class,
        'primary_role_abilities' => [

            /* Example

            'primary_role_1' => [
//            'ability_foo' => ['primary_role_1', 'primary_role_2'],
            ],

            'primary_role_2' => [
//            'ability_bar' => ['primary_role_2'],
            ],

            */
        ],
    ],

    'target_self_overrides' => [
        'implementation_class' => TargetSelfOverrides::class,
        'ability_overrides'    => [
            /* Example

                'delete' => false,

            */
        ],
    ],
];
