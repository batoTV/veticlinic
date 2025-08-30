<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_VET = 'vet';
    const ROLE_RECEPTIONIST = 'receptionist';
    const ROLE_ASSISTANT = 'assistant';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add role here
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper functions to check roles
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isVet()
    {
        return $this->hasRole(self::ROLE_VET);
    }
    
    public function isReceptionist()
    {
        return $this->hasRole(self::ROLE_RECEPTIONIST);
    }
}
