<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id', 'phone_number', 'address', 'about_me'
    ];

    public function user() {
        return $this->hasOne(User::class,'id', 'user_id');
    }
}
