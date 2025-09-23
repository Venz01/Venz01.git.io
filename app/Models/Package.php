<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'price', 'pax', 'status', 'image_path'
    ];

    public function items()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_package', 'package_id', 'menu_item_id')
                    ->withTimestamps();
    }
}

