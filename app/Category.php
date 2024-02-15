<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;

class Category extends Model
{
    // use HasApiTokens;

    protected $fillable = [
        'name',
        't_name',
        't_type',
        'l_type',
    ];
    protected $table = 'categories';
    public function item()
    {
        return $this->hasMany(Item::class);
    }
}
