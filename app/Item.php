<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class Item extends Model
{
    protected $fillable = [
        "name", 
        "category_id",
        "properties",
        "departments",
        "training_date",
        "expired_date",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
