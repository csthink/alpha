<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * 用户删除策略(只能是管理员删除，且不能删除自己)
     * @param User $currentUser
     * @param Status $status
     * @return bool
     */
    public function destroy(User $currentUser, Status $status)
    {
        return $currentUser->id === $status->user_id;
    }
}
