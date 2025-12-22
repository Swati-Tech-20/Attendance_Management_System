<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

   

    // Define the roles relationship
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_users', 'user_id', 'role_id');
    }

    // Check if the user has any of the given roles
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            return $this->hasRole($roles);
        }

        return false;
    }

    // Check if the user has a specific role
    public function hasRole($role)
    {
        // Get the currently authenticated user
        $user = auth()->user();

        // Check if any role with the given slug exists for the user
        return $user->roles()->where('slug', $role)->exists();
    }

    protected $fillable = [
        'name', 'email', 'password', 'is_admin',
    ];

    protected $hidden = [
        'pin_code', 'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employees()
{
    return $this->hasMany(Employee::class);
}
}
