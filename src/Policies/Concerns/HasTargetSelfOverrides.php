<?php


namespace UnstoppableCarl\Arbiter\Policies\Concerns;

use UnstoppableCarl\Arbiter\TargetSelfOverrides;

trait HasTargetSelfOverrides
{
    /**
     * @var TargetSelfOverrides
     */
    protected $targetSelfOverrides;

    /**
     * @param $user
     * @param $ability
     * @param $target
     * @return mixed|null
     */
    public function before($user, $ability, $target)
    {
        return $this->targetSelfOverrides->callBefore(func_get_args());
    }
}
