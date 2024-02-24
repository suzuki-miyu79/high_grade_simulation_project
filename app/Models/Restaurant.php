<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Favorite;
use App\Models\Reservation;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';
    protected $fillable = ['area_id', 'genre_id', 'name', 'overview', 'image', 'user_id'];

    // ユーザーがこのレストランをお気に入りに追加しているかどうかを判定
    public function isFavoritedByUser($user)
    {
        // $userがnullでないことと、$user->favoritesがnullでないことを確認
        return $user && $user->favorites && $user->favorites->contains('restaurant_id', $this->id);
    }

    // Area モデルとのリレーションシップ
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    // Genre モデルとのリレーションシップ
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    // User モデルとのリレーションシップ
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Favorite モデルとのリレーションシップ
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Reservation モデルとのリレーションシップ
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Representativeモデルとのリレーションシップ
    public function representatives()
    {
        return $this->hasMany(Representative::class);
    }
}
