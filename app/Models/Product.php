<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, UUIDAsPrimaryKey;
    protected $guarded;

    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }
}
