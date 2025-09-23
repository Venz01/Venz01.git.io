<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    // Role constants
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_CATERER = 'caterer';
    public const ROLE_ADMIN = 'admin';

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'business_name',
        'owner_full_name',
        'business_address',
        'business_permit_number',
        'business_permit_file_path',
        'business_permit_photo_path',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    // Check for specific role
    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isCaterer()
    {
        return $this->role === self::ROLE_CATERER;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
