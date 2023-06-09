<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email_queue extends Model
{
    protected $table = 'email_queue';
    protected $guarded = [];
    use HasFactory;
}
