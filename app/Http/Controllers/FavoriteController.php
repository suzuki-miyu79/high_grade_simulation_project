<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request, $restaurantId)
    {
        // ログイン確認
        $user = auth()->user();

        // お気に入り登録されているか確認
        $favorite = Favorite::where('user_id', $user->id)
            ->where('restaurant_id', $restaurantId)
            ->first();

        if ($favorite) {
            // お気に入りがすでに存在する場合は削除
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            // お気に入りが存在しない場合は追加
            Favorite::create([
                'user_id' => $user->id,
                'restaurant_id' => $restaurantId,
            ]);

            return response()->json(['status' => 'added']);
        }
    }
}
