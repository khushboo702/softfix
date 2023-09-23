<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'description', 'image'];

    public function postData()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }
}
