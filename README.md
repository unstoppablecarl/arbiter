# Arbiter

Manage Laravel User abilities that target Users.

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

## About

Determining a way to authorize what actions can be performed by one User on another may seem like a simple problem at first.
Most Role based permission modules are designed to allow multiple roles per user. 
This is an extremely powerful and flexible design pattern but creates a hard to define authorization case: 
When User-A can *update* users with Role-1, and User-B has Role-1 and Role-2, how should your application determine if User-A *update* User-B?

Arbiter provides a solution to this problem without getting in the way of an existing or separate multi-role based authorization system.

## Requirements

 - PHP >= 5.5.9
 - Laravel >= 5.2

## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. 
Run the following command to install the package and add it as a requirement to your project's `composer.json`:

```bash
composer require unstoppablecarl/arbiter
```

## Overview

Each User has exactly one **Primary Role**. Primary Roles are used to determine what actions a user can perform on other users and vice-versa. Each Primary Role is identified with a unique name string.

The `UserWithPrimaryRole` interface is implemented on the User model.

```php
<?php
interface UserWithPrimaryRole {

    /*
     * Get the Primary Role of this user.
     * @return string 
     */
    public function getPrimaryRoleName();
}
```

The developer implements the interface with a strategy for determining what the Primary Role of a user is. 

### Primary Role Implementation Strategies

 - Using an existing multi-role based system:
   - Define some roles as "Primary". Each User has exactly one Primary Role.
   - Define a numeric priority to each Role. The Primary Role of a User is the Role with the highest priority assigned to them.
 - Users have exactly one Role which is used as the Primary Role. This is often a good starting point projects where it is unclear how complex the roles/permissions requirements will become. 

### In Depth

The files are well commented with clear explanations.

[`UserAuthorityContract`](src/Contracts/UserAuthorityContract.php)

Provides a simple interface for checking what abilities a *Primary Role* has when targeting another *Primary Role*.

[`UnstoppableCarl\Arbiter\UserAuthority`](src/UserAuthority.php)

A simple array configuration based implementation is available here.

[`UnstoppableCarl\Arbiter\Policies\UserPolicy`](src/Policies/UserPolicy.php)

Leverages the `UserAuthority` for authorizing actions. It also can override abilities when a user is targeting them self.

## Usage

Implement the [`UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole`](src/Contracts/UserWithPrimaryRole.php) Contract on your `User` model.

```php
<?php

namespace App\User;

use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole;

class UserPolicy implements UserWithPrimaryRole
{
    public function getPrimaryRoleName()
    {
        // @TODO implement Primary Role strategy
        return false;
    }
} 
```

Extend `App\Policies\UserPolicy` with [`UnstoppableCarl\Arbiter\Policies\UserPolicy`](src/Policies/UserPolicy.php)
```php
<?php

namespace App\Policies;

use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole;
use UnstoppableCarl\Arbiter\Policies\UserPolicy as ArbiterUserPolicy;

class UserPolicy extends ArbiterUserPolicy
{
    /**
     * Override the returned value when a User is targeting self. 
     * @var array
     */
    protected $overrideWhenSelf = [
        'update'            => true,
        'delete'            => false,
        'changePrimaryRole' => false,
    ];

    /**
     * Boilerplate for adding a User Policy ability.
     * @param UserWithPrimaryRole $source
     * @param UserWithPrimaryRole|null $target
     * @return
     */
    public function myCustomAbility(UserWithPrimaryRole $source, $target = null)
    {
        $source  = $this->toPrimaryRole($source);
        $target  = $this->toPrimaryRole($target);
        $ability = 'my_ability_name';
        return $this->userAbilities->canOrAny($source, $ability, $target);
    }
}
```


## Running Tests

Run Unit Tests

```
$ composer phpunit
```

Run Codesniffer (psr-2)
```
$ composer phpcs
```

Run both

```
$ composer test
```

## Contributing

Contributions and Pull Requests welcome!

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.
 
## Authors

* **Carl Olsen** - *Initial work* - [Unstoppable Carl](https://github.com/unstoppablecarl)

See also the list of [contributors][] who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

[composer]: http://getcomposer.org/
[contributors]: https://github.com/unstoppablecarl/arbiter/contributors
[packagist]: https://packagist.org/packages/unstoppablecarl/arbiter

[badge-source]: https://img.shields.io/badge/source-unstoppablecarl/arbiter-blue.svg
[badge-release]: https://img.shields.io/packagist/v/unstoppablecarl/arbiter.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/unstoppablecarl/arbiter/master.svg?style=flat-square
[badge-coverage]: https://img.shields.io/coveralls/unstoppablecarl/arbiter/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/unstoppablecarl/arbiter.svg?style=flat-square

[source]: https://github.com/unstoppablecarl/arbiter
[release]: https://packagist.org/packages/unstoppablecarl/arbiter
[license]: https://github.com/unstoppablecarl/arbiter/blob/master/LICENSE
[build]: https://travis-ci.org/unstoppablecarl/arbiter
[coverage]: https://coveralls.io/r/unstoppablecarl/arbiter?branch=master
[downloads]: https://packagist.org/packages/unstoppablecarl/arbiter