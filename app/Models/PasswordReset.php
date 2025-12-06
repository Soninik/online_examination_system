<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'email',
        'token'
    ];

    public $timestamps = false;

    protected $primaryKey = 'email';
}
