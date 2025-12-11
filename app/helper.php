<?php

use App\Models\Subject;

if (!function_exists('getSubject')) {
    function getSubject()
    {
        return Subject::select('subject', 'id')->get();
    }
}
