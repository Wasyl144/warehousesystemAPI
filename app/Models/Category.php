<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'node_id', 'name'
    ];

    public function childs() {
        return $this->hasMany(Category::class, 'node_id');
    }

    public function parents() {
        return $this->belongsTo(Category::class, 'node_id');
    }
}
