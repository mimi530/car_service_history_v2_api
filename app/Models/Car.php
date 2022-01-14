<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'milage'
    ];

    protected $casts = [
        'user_id' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(
            fn($model) => $model->uuid = (string) Str::uuid()
        );
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }
}
