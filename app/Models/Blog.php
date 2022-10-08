<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'start_date',
        'end_date',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function scopeActive($query)
    {
        $query = $query->where('active', '=', 1);
    }

    public function scopeNotexpired($query)
    {
        $date = date("Y-m-d");
        $query = $query->where('start_date', '<=', $date)->where('end_date', '>=', $date);
    }
}
