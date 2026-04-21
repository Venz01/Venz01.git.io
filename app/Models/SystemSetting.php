<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get setting value by key with fallback default.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::query()->where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set (create/update) setting by key.
     */
    public static function setValue(string $key, $value): self
    {
        return static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );
    }
}
