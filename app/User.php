<?php

namespace App;

use Illuminate\Notifications\Notifiable;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Notifiable;
    use Authenticatable, Authorizable, CanResetPassword;

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getSection(){
        return 'user';
    }

    public function passwordSecurity()
    {
        return $this->hasOne('App\PasswordSecurity');
    }

    public function role(){
        return $this->hasOne(\App\Role::class, 'id', 'role_id');
    }

    public function isSuperAdmin(){
        return $this->role->is_super_admin ?? false;
    }

    public function hasPermission($section, $type = 'view'){
        $permissions = json_decode($this->role->permissions, true);
        return $this->role->is_super_admin || (isset($permissions[$section][$type]) && $permissions[$section][$type] === '1');
    }
    
    public function hasPermissionIn($sections, $type = 'view'){
        foreach ($sections as $key => $section) {
            if($this->hasPermission($section, $type)){
                return true;
            }
        }
        return false;
    }
}
