<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['image_id', 'ip_address', 'content'];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
