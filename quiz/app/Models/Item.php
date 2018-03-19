<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = ['category_id', 'name', 'price', 'stock'];
    protected $guarded = [];

    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }
}
