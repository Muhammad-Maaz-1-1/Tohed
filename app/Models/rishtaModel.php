<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rishtaModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'gender',
        'birthdate',
        'marital_status',
        'city',
        'country',
        'mother_tongue',
        'education',
        'profession',
        'ethnicity',
        'image',
        'content',
        'status',
    ];
}
