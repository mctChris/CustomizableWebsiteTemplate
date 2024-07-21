<?php

namespace App\Policies;

use App\User;
use App\BaseModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the base model.
     *
     * @param  \App\User  $user
     * @param  \App\BaseModel  $baseModel
     * @return mixed
     */

    public function view(User $user, BaseModel $baseModel)
    {
        return $user->hasPermission($baseModel->getSection(), 'view');
    }

    public function update(User $user, BaseModel $baseModel)
    {
        return $user->hasPermission($baseModel->getSection(), 'update') && !$baseModel->read_only;
    }

    public function delete(User $user, BaseModel $baseModel)
    {

        // disallow delete if category has child
        if($baseModel->is_category && (!empty($baseModel->childs->toArray()) || !empty($baseModel->child_cats->toArray()))){
            return false;
        }

        // disallow delete for page
        if($baseModel->is_page){
            return false;
        }

        return ($baseModel->exists || $baseModel->config_model)  && $user->hasPermission($baseModel->getSection(), 'delete');
    }

    public function back(User $user, BaseModel $baseModel)
    {
        // disallow back for page
        return !$baseModel->is_page;
    }

}
