<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * 用户更新策略(只能是当前登录用户更新自己的)
     * @param User $currentUser 默认为当前登录用户实例
     * @param User $user 要进行授权的用户实例
     * @return bool 是否通过授权,否则会抛出一个403异常
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * 用户删除策略(只能是管理员删除，且不能删除自己)
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
