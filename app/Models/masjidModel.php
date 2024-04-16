<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masjidModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', // Add other fields here as well if needed
        'masjid_name',
        'masjid_address',
        'city',
        'country',
        'imam_name',
        'images',
        'khateeb_name',
        'contact_number',
        'created_at',
        'updated_at',
    ];
}
