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

## Basic Usage


### User

Implement the `UserWithPrimaryRole` Interface on your `User` model.

See [`UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole`](src/Contracts/UserWithPrimaryRole.php)

```php
<?php

namespace App;

use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole;

class User implements UserWithPrimaryRole
{
    public function getPrimaryRoleName()
    {
        // @TODO implement Primary Role strategy
        
        // simple example
        // not recommended
        return $this->primary_role ?: 'default_primary_role';
    }
} 
```

### User Policy

Create `App\Policies\UserPolicy` and set it as the policy for the `User` model in `App\Providers\AuthServiceProvider`

See [`UserPolicy`](src/UnstoppableCarl\Arbiter\Policies\UserPolicy.php)


```php
<?php

namespace App\Policies;

use UnstoppableCarl\Arbiter\Policies\UserPolicy as ArbiterUserPolicy;

class UserPolicy extends ArbiterUserPolicy
{

}
```

### User Authority

Create and bind an implementation of the `UserAuthorityContract` in your `AuthServiceProvider` or continue with the Config Based User Authority below.

#### Config Based Implementation

Arbiter includes a simple config based `UserAuthority` implementation to quickly get your project up and running. 
 
##### ArbiterServiceProvider

Add the Service Provider to `config/app.php`

`UnstoppableCarl\Arbiter\Providers\ArbiterServiceProvider::class,`

##### Configure

Publish the config file.

`php artisan vendor:publish --provider=UnstoppableCarl\Arbiter\Providers\ArbiterServiceProvider`

Primary Role Abilities can be configured in `config/arbiter.php`.


## Customizing The User Policy

The `UserPolicy` functionality is organized into seperate traits to allow use of only the functionality you want.

 - See
   - [`Concerns`](src/Policies/Concerns)
   - [`UserPolicy`](src/Policies/UserPolicy.php)


#### UserPolicy Trait: HasUserAuthority

[`HasUserAuthority`](src/Policies/Concerns/HasUserAuthority.php)

Adds a reference to the `UserAuthority` instance. 

 - Required for `HasAbilities` and `HasGetters` traits.
 - Gets the primary role of ability targets that implement the `UserWithPrimaryRole` interface via a `toPrimaryRole` method.

#### Trait: HasAbilities

[`HasAbilities`](src/Policies/Concerns/HasAbilities.php)
 
Adds the typical abilities of a `UserPolicy` matching them to the methods and abilities of the `UserAuthority`.
 
 - Requires `HasUserAuthority` trait.
 - Methods
   - create
   - update
   - delete
   - view
   - changePrimaryRoleFrom
   - changePrimaryRoleTo 
   - changePrimaryRole


#### Trait: HasGetters

[`HasGetters`](src/Policies/Concerns/HasGetters.php)

Adds getters to allow retrieval of all primary roles a user can perform given abilities on.

 - Requires `HasUserAuthority` trait.
 - Methods
   - getViewablePrimaryRoles
   - getCreatablePrimaryRoles
   - getChangeableFromPrimaryRoles
   - getChangeableToPrimaryRoles
   - getDeletablePrimaryRoles
   - getPrimaryRoles
   


#### Trait: HasTargetSelfOverrides

[`HasTargetSelfOverrides`](src/Policies/Concerns/HasTargetSelfOverrides.php)

Allows overriding the returned value of a `UserPolicy` ability check, when the source and target of the check are the same `User`. The ability check is overriden by using the `before` method behavior of Laravel Policies.

- The `$targetSelfOverrides` property must be set to an implementation of the [`TargetSelfOverridesContract`](src/Contracts/TargetSelfOverridesContract.php). In the included [`UserPolicy`](src/UnstoppableCarl\Arbiter\Policies\UserPolicy.php) it is set via the constructor.
-  [`TargetSelfOverrides`](src/TargetSelfOverrides.php) is a minimal implementation included and used by default in the [`ArbiterServiceProvider`](src/Providers/ArbiterServiceProvider).

#### Adding User Authority Abilities

The following shows how to add an ability to the `UserPolicy` that checks a custom ability set in the `UserAuthority`. 

```php
<?php

namespace App\Policies;

use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole;
use UnstoppableCarl\Arbiter\Policies\UserPolicy as ArbiterUserPolicy;

class UserPolicy extends ArbiterUserPolicy
{
    /**
     * Can ban users with $target Primary Role
     * @param UserWithPrimaryRole $source
     * @param UserWithPrimaryRole|null $target
     * @return
     */
    public function ban(UserWithPrimaryRole $source, $target = null)
    {
        $source  = $this->toPrimaryRole($source);
        $target  = $this->toPrimaryRole($target);
        $ability = 'ban';
        return $this->userAuthority()->canOrAny($source, $ability, $target);
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
