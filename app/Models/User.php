<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // Role constants
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_CATERER = 'caterer';
    public const ROLE_ADMIN = 'admin';

    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'bio',
        'profile_photo',
        // Caterer fields
        'business_name',
        'owner_full_name',
        'business_address',
        'business_permit_number',
        'business_permit_file_path',
        'business_permit_photo_path',
        'services_offered',
        'cuisine_types',
        'years_of_experience',
        'team_size',
        'service_areas',
        'facebook_link',
        'instagram_link',
        'website_link',
        'contact_number',
        'other_contact',
        'business_hours_start',
        'business_hours_end',
        'business_days',
        'minimum_order',
        'maximum_capacity',
        'offers_delivery',
        'offers_setup',
        'special_features',
        'status',
        // Customer fields
        'preferred_cuisine',
        'default_address',
        'city',
        'postal_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'cuisine_types' => 'array',
            'service_areas' => 'array',
            'business_days' => 'array',
            'offers_delivery' => 'boolean',
            'offers_setup' => 'boolean',
        ];
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function portfolioImages()
    {
        return $this->hasMany(PortfolioImage::class)->ordered();
    }

    public function featuredImages()
    {
        return $this->hasMany(PortfolioImage::class)->featured()->ordered();
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

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

    public function getCuisineTypesStringAttribute()
    {
        return is_array($this->cuisine_types) ? implode(', ', $this->cuisine_types) : '';
    }

    public function getServiceAreasStringAttribute()
    {
        return is_array($this->service_areas) ? implode(', ', $this->service_areas) : '';
    }

    public function getBusinessDaysStringAttribute()
    {
        return is_array($this->business_days) ? implode(', ', array_map('ucfirst', $this->business_days)) : '';
    }
}