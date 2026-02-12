<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DietaryTag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'is_system'
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);

                // Ensure slug uniqueness
                $count = 1;
                $originalSlug = $tag->slug;

                while (static::where('slug', $tag->slug)->exists()) {
                    $tag->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    /**
     * Scope for non-system tags
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Scope for system tags
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Packages with this tag
     */
    public function packages()
    {
        return Package::whereJsonContains('dietary_tags', $this->slug)->get();
    }

    /**
     * Users with this dietary preference
     */
    public function users()
    {
        return User::whereJsonContains('dietary_preferences', $this->slug)->get();
    }
}
