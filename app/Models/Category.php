<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table="Categories";
    public function Image()
    {
        
    return $this->hasMany(Image::class, 'category_id','dataType'. 'category_id');
    }
}
