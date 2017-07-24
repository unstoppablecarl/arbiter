<?php

namespace UnstoppableCarl\Arbiter\Contracts;

/**
 * Manages the permissions related to
 * users viewing/creating/updating/deleting/changing the primary roles of, other users
 * Class UserAuthority
 * @package PrimedRoles\Contracts
 */
interface UserAuthorityContract
{
    /**
     * Check if $source primary role can perform $ability on $target primary role.
     * @param string $source primary role
     * @param string $ability
     * @param string $target primary role
     * @return bool
     */
    public function can($source, $ability, $target);

    /**
     * Check if $source primary role can perform $ability on ANY primary roles.
     * @param string $source primary role
     * @param string $ability
     * @return bool
     */
    public function canAny($source, $ability);

    /**
     * Check if $source primary role can perform $ability on $target primary role.
     * if $target is null Check if $source primary role can perform $ability on any $target.
     * @param string $source primary role
     * @param string $ability
     * @param string|null $target primary role
     * @return mixed
     */
    public function canOrAny($source, $ability, $target = null);

    /**
     * Check if $source can view $target.
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param string $source primary role
     * @param string|null $target primary role
     * @return bool
     */
    public function canView($source, $target = null);

    /**
     * Check if $source can create $target.
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param string $source primary role
     * @param string|null $target primary role
     * @return bool
     */
    public function canCreate($source, $target = null);

    /**
     * Check if $source can update $target.
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param string $source primary role
     * @param string|null $target primary role
     * @return bool
     */
    public function canUpdate($source, $target = null);

    /**
     * Check if $source can delete $target.
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param string $source primary role
     * @param string|null $target primary role
     * @return bool
     */
    public function canDelete($source, $target = null);

    /**
     * Check if $source can change primary role of a user with a primary role of $target.
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param string $source primary role
     * @param string|null $target primary role
     * @return bool
     */
    public function canChangePrimaryRoleFrom($source, $target = null);

    /**
     * Check if $source can change primary role of a user to a $target.
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param string $source primary role
     * @param string|null $target primary role
     * @return bool
     */
    public function canChangePrimaryRoleTo($source, $target = null);

    /**
     * Get list of primary role names.
     * @return array
     */
    public function getPrimaryRoles();

    /**
     * Get primary roles the $source can perform $ability on.
     * @param string $source source primary role
     * @param string $ability
     * @return array
     */
    public function get($source, $ability);

    /**
     * Get primary roles that a user must have to be viewed by $source.
     * @param string $source primary role
     * @return array
     */
    public function getViewablePrimaryRoles($source);

    /**
     * Get primary roles that $source can set when creating a User.
     * Helper for populating primary role dropdown when creating users.
     * @param string $source primary role
     * @return array
     */
    public function getCreatablePrimaryRoles($source);

    /**
     * Get primary roles that $source can change the primary role of.
     * @param string $source primary role
     * @return array
     */
    public function getUpdatableFromPrimaryRoles($source);

    /**
     * Get primary roles that $source can change on $target.
     * If $target is null, gets all primary roles $source can potentially update a $target to.
     * Helper for populating a primary role dropdown when updating a user.
     * @param string $source primary role
     * @param string|null $target primary role
     * @return array
     */
    public function getUpdatableToPrimaryRoles($source, $target = null);

    /**
     * Get primary roles that a user must have to be deleted by $source.
     * @param string $source primary role
     * @return array
     */
    public function getDeletablePrimaryRoles($source);
}
