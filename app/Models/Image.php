<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table="Images";
    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id','dataType', 'category_id');
       
       
    }
}
