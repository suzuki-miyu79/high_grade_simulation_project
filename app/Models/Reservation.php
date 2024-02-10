<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Restaurant;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $fillable = ['user_id', 'restaurant_id', 'date', 'time', 'number'];

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

    // 予約が過去の日時かどうかを判定するメソッド
    public function hasPassed()
    {
        // 予約日時をCarbonオブジェクトに変換して現在時刻と比較
        return Carbon::parse($this->date . ' ' . $this->time) < Carbon::now();
    }
}
