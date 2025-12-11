<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'subject'
    ];

    public function exam()
    {
        return $this->hasMany(Exam::class);
    }
}
