<?php

namespace App\Policies;

use App\User;
use App\BaseModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the base model.
     *
     * @param  \App\User  $user
     * @param  \App\BaseModel  $role
     * @return mixed
     */


    public function delete(User $user, BaseModel $role)
    {

        if($role->is_super_admin){
            return false;
        }

        return parent::delete($user, $role);
    }

}
