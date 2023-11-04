<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $casts = [
        'date_start' => 'datetime:Y-m-d h:i:s',
        'date_end' => 'datetime:Y-m-d h:i:s',
        'questions' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz_access()
    {
        return $this->hasMany(Quiz_access::class);
    }

    public function quiz_user()
    {
        return $this->hasMany(Quiz_user::class);
    }

}
