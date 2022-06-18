<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['code', 'title', 'description', 'extension', 'mime_type', 'size', 'file_path', 'real_path', 'absolute_path'];
}
