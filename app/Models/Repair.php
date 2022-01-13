<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'date', 'milage', 'description'
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

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
