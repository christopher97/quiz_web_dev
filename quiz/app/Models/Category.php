<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = ['name'];
    protected $guarded = [];

    public function items() {
        return $this->hasMany('App\Models\Item', 'category_id', 'id');
    }
}
