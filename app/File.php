<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $fillable = [
        'name', 
        'path', 
        'mime', 
        'size'
    ];
    public function item()
    {
        return $this->hasOne(Item::class, 'files_id');
    }
}
