<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notifications';
    protected $casts = [
        'data' => 'array',
        'id' => 'string',
    ];
}
