<?php

namespace Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $fillable = [
        'property',
    ];
}