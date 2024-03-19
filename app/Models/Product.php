<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'price'];

    protected $dates = ['deleted_at'];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
