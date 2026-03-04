<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'gender',
        'ip_address',
        'company',
        'city',
        'title',
        'website',
    ];
}
