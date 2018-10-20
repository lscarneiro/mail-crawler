<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    public $timestamps = false;

    protected $fillable = ['url', 'visited'];

    protected $casts = [
        'visited' => 'boolean',
    ];
}
