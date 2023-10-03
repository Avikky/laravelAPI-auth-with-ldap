<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ApiToken extends Model
{
    use HasFactory;

    protected $connection = 'mainDemand';
    protected $table = 'api_tokens';

    protected $fillable = [
        'user_id',
        'token_id',
        'token',
        'refreshed',
        'expired',
        'expires_at',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         // Set the 'created_by' column to the currently authenticated user's ID
    //         $model->token_id = env('APITokenID');
    //     });
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
