<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'name', 'id_category', 'location', 'quantity', 'description'
    ];

    protected $with = ['category'];

    public function category() {
        return $this->hasOne(Category::class, 'id', 'id_category');
    }

}
