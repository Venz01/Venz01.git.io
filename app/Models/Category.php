<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['user_id', 'name'];

    public function items() {
        return $this->hasMany(MenuItem::class);
    }
}