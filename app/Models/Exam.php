<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'exam_name',
        'subject_id',
        'exam_date',
        'exam_time',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
