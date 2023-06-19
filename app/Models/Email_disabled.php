<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email_disabled extends Model
{
    protected $table = 'disabled_users';
    use HasFactory;
}
