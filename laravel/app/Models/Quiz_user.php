<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz_user extends Model
{
    use HasFactory;

    protected $table = 'quiz_users';
    protected $casts = [
        'time_start' => 'datetime:Y-m-d h:i:s',
        'time_end' => 'datetime:Y-m-d h:i:s',
        'questions' => 'array',
        'overlay_views' => 'array',
        'client' => 'array'
    ];
}
