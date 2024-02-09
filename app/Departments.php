<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // If your database table is named 'departments' you don't need to specify it,
    // Laravel will assume this by default. If it's named differently, specify it like below:
    // protected $table = 'your_table_name';

    // If your department table's primary key is not 'id' or you want to set it to a non-incrementing
    // or a non-numeric primary key, you need to specify it here:
    // protected $primaryKey = 'your_primary_key';
    // public $incrementing = false;
    // protected $keyType = 'string';

    // By default, Eloquent expects created_at & updated_at columns. If you don't have these, set the following to false:
    // public $timestamps = false;

    // If you need to specify the connection name:
    // protected $connection = 'your_connection_name';

    // Fillable attributes for mass assignment.
    // Replace 'column1', 'column2', ..., 'columnN' with the actual column names you want to make fillable.
    protected $fillable = [
        'dept_name',
        // ... any other column names that you want to be fillable
    ];

    // If you want to make all attributes mass assignable by default, you can set the guarded property to an empty array:
    // protected $guarded = [];

    // If your department has relationships with other tables, define them here:
    // For example, if each department can have many employees:
    public function item()
    {
        return $this->hasMany('App\Item'); // Employee is another model that represents an employee table
    }

    // Any other methods you want to define for the Department model...
}
