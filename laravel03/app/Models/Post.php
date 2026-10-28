<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $table = 'posts';
    protected $dates = ['deleted_at'];
    protected $dateFormat = 'Y-m-d H:i:s';

    public function scopeLaravel($query)
    {
        return $query->where('title', 'like', '%Laravel%');
    }

    public function scopeGetUser2($query) 
    {
        return $query->where('user_id', 2);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // mặc định laravel là lấy cột tên table_id để làm khoá phụ, nhưng mình muốn lấy cột user_id để làm khoá phụ
    }
}