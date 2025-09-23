<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'description',
        'price',
        'image_path',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'menu_item_package', 'menu_item_id', 'package_id')
                    ->withTimestamps();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


