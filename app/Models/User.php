<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\DisplayMenu;

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
        'rejection_reason',
        // Customer fields
        'preferred_cuisine',
        'default_address',
        'city',
        'postal_code',
        // Dietary fields
        'dietary_preferences',
        'food_allergies',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'cuisine_types'       => 'array',
            'service_areas'       => 'array',
            'business_days'       => 'array',
            'offers_delivery'     => 'boolean',
            'offers_setup'        => 'boolean',
            'dietary_preferences' => 'array',   // ← new
        ];
    }

    /**
     * Resolve a display-safe profile photo URL with fallback avatar.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        $photo = trim((string) $this->profile_photo);

        if ($photo === '') {
            return asset('images/default-avatar.svg');
        }

        // Cloudinary/external absolute URL - use as-is.
        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            return $photo;
        }

        // Local public storage URL already saved as /storage/...
        if (str_starts_with($photo, '/storage/')) {
            return asset(ltrim($photo, '/'));
        }

        // Local public storage URL accidentally saved as storage/...
        if (str_starts_with($photo, 'storage/')) {
            return asset($photo);
        }

        // Plain local disk path, example: profile-photos/avatar.jpg
        return asset('storage/' . ltrim($photo, '/'));
    }



    /**
     * Resolve a display-safe business permit document URL.
     * Supports Cloudinary/external URLs and local public storage paths.
     */
    public function getBusinessPermitFileUrlAttribute(): ?string
    {
        $file = trim((string) $this->business_permit_file_path);

        if ($file === '') {
            return null;
        }

        // Cloudinary/external absolute URL - use as-is.
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            return $file;
        }

        // Local public storage URL already saved as /storage/...
        if (str_starts_with($file, '/storage/')) {
            return asset(ltrim($file, '/'));
        }

        // Local public storage URL accidentally saved as storage/...
        if (str_starts_with($file, 'storage/')) {
            return asset($file);
        }

        // Plain local disk path, example: business-permits/file.jpg
        return asset('storage/' . ltrim($file, '/'));
    }

    // ─────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────

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

    public function displayMenus()
    {
        return $this->hasMany(DisplayMenu::class, 'user_id');
    }

    public function activeDisplayMenus()
    {
        return $this->hasMany(DisplayMenu::class, 'user_id')->where('status', 'active');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'caterer_id');
    }

    public function customerBookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    // ─────────────────────────────────────────────
    // Role helpers
    // ─────────────────────────────────────────────

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

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function hasRejectionReason(): bool
    {
        return ! empty($this->rejection_reason);
    }

    // ─────────────────────────────────────────────
    // Dietary helpers
    // ─────────────────────────────────────────────

    /**
     * Human-readable labels for each preference key.
     */
    public static function dietaryLabels(): array
    {
        $tags = \App\Models\DietaryTag::all();
        $labels = [];
        
        foreach ($tags as $tag) {
            $labels[$tag->slug] = $tag->name;
        }
        
        return $labels;
    }

    /**
     * Returns true when the customer has at least one dietary preference saved.
     */
    public function hasDietaryPreferences(): bool
    {
        return ! empty($this->dietary_preferences);
    }

    /**
     * Returns true when the customer has saved food allergies text.
     */
    public function hasFoodAllergies(): bool
    {
        return ! empty($this->food_allergies);
    }

    /**
     * Checks whether a given preference key is set for this customer.
     */
    public function hasDietaryPreference(string $key): bool
    {
        return in_array($key, (array) ($this->dietary_preferences ?? []), true);
    }

    /**
     * Returns an array of the customer's dietary preferences as human-readable labels.
     */
    public function getDietaryLabelsAttribute(): array
    {
        $labels = static::dietaryLabels();
        return array_values(array_map(
            fn ($key) => $labels[$key] ?? ucfirst(str_replace('_', ' ', $key)),
            (array) ($this->dietary_preferences ?? [])
        ));
    }

    // ─────────────────────────────────────────────
    // String accessors
    // ─────────────────────────────────────────────

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
        return is_array($this->business_days)
            ? implode(', ', array_map('ucfirst', $this->business_days))
            : '';
    }

    // ─────────────────────────────────────────────
    // Review relationships & stats
    // ─────────────────────────────────────────────

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'caterer_id');
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class, 'caterer_id')->where('is_approved', true);
    }

    public function averageRating()
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function totalReviews()
    {
        return $this->approvedReviews()->count();
    }

    public function ratingDistribution()
    {
        return [
            5 => $this->approvedReviews()->where('rating', 5)->count(),
            4 => $this->approvedReviews()->where('rating', 4)->count(),
            3 => $this->approvedReviews()->where('rating', 3)->count(),
            2 => $this->approvedReviews()->where('rating', 2)->count(),
            1 => $this->approvedReviews()->where('rating', 1)->count(),
        ];
    }

    public function responseRate()
    {
        $total = $this->approvedReviews()->count();
        if ($total === 0) return 0;

        $responded = $this->approvedReviews()->whereNotNull('caterer_response')->count();
        return round(($responded / $total) * 100, 1);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'caterer_id');
    }
}