<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    use HasFactory;

    protected $table = 'representatives';
    protected $fillable = ['user_id', 'restaurant_id'];

    // User モデルとのリレーションシップ
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Restaurant モデルとのリレーションシップ
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
