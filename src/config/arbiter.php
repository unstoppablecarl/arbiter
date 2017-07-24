<?php

return [
    'override_when_self'     => [
        // change to false to prevent users from changing their Primary Role.
        'changePrimaryRole' => null,
        // change to false to prevent users from deleting themselves.
        'delete'            => null,
    ],
    'primary_role_abilities' => [

        'primary_role_1' => [
//            'ability_foo' => ['primary_role_1', 'primary_role_2'],
        ],

        'primary_role_2' => [
//            'ability_bar' => ['primary_role_2'],
        ],
    ],
];
