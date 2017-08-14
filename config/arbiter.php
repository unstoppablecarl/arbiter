<?php

use UnstoppableCarl\Arbiter\UserAuthority;

return [

    'user_authority' => [

        /**
         * Abilities Primary roles can perform on other Primary Roles
         */
        'primary_role_abilities' => [

            /* Example Values

            'role_1' => [
                'ability_a' => ['role_1', 'role_2'],
                'ability_b' => ['role_1', 'role_2'],
            ],

            'role_2' => [
                'ability_b' => ['role_2'],
            ],

            */

            /* Boilerplate with built in keys

            'admin' => [
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                => ['admin'],
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE => [],
                UserAuthority::CAN_VIEW_USERS_WITH_PRIMARY_ROLE                   => [],
                UserAuthority::CAN_CREATE_USERS_WITH_PRIMARY_ROLE                 => [],
                UserAuthority::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                 => [],
                UserAuthority::CAN_DELETE_USERS_WITH_PRIMARY_ROLE                 => [],
            ],

            */
        ],
    ],

    'target_self_overrides' => [

        /**
         * Overrides the returned value of an ability check when $target is the currently logged in user.
         */
        'ability_overrides' => [
            /* Example Values

                'delete' => false,
                'view'   => true,

            */
        ],
    ],
];
