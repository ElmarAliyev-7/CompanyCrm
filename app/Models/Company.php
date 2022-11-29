<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'about',
        'logo',
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_companies');
    }
}
