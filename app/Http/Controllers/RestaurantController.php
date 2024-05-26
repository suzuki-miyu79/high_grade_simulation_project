<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ImportRestaurantRequest;
use App\Http\Requests\ValidateRestaurantRecord;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Restaurant;
use App\Models\Review;
use League\Csv\Reader;


class RestaurantController extends Controller
{
    // 店舗情報新規登録
    public function store(Request $request)
    {
        // ユーザーIDを取得
        $userId = auth()->id();

        // 画像のアップロード処理
        if ($request->hasFile('image')) {
            // 画像を保存してパスを取得
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // 新しい店舗を作成
        $restaurant = new Restaurant();
        $restaurant->name = $request->input('name');
        $restaurant->area_id = $request->input('area');
        $restaurant->genre_id = $request->input('genre');
        $restaurant->overview = $request->input('overview');
        $restaurant->user_id = $userId;
        // 画像がアップロードされていれば、パスを保存
        if (isset($imagePath)) {
            $restaurant->image = $imagePath;
        }
        $restaurant->save();

        // 成功メッセージをフラッシュしてリダイレクト
        return redirect()->back()->with('success', '店舗が登録されました。');
    }

    // 店舗情報更新
    public function update(Request $request, $id)
    {
        // 画像のアップロード処理
        if ($request->hasFile('image')) {
            // 画像を保存してパスを取得
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // 既存の店舗情報を取得
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->name = $request->input('name');
        $restaurant->area_id = $request->input('area');
        $restaurant->genre_id = $request->input('genre');
        $restaurant->overview = $request->input('overview');
        // 画像がアップロードされていれば、パスを保存
        if (isset($imagePath)) {
            $restaurant->image = asset('storage/' . $imagePath);
        }
        $restaurant->save();

        // 成功メッセージをフラッシュしてリダイレクト
        return redirect()->back()->with('success', '店舗情報が更新されました。');
    }

    // 飲食店一覧取得
    public function index(Request $request)
    {
        $areas = Area::whereIn('id', function ($query) {
            $query->select('area_id')->from('restaurants')->distinct();
        })->get();

        $genres = Genre::whereIn('id', function ($query) {
            $query->select('genre_id')->from('restaurants')->distinct();
        })->get();

        $restaurants = Restaurant::when($request->area, function ($query, $area) {
            return $query->whereHas('area', function ($subquery) use ($area) {
                $subquery->where('prefectures_name', $area);
            });
        })
            ->when($request->genre, function ($query, $genre) {
                return $query->whereHas('genre', function ($subquery) use ($genre) {
                    $subquery->where('genre_name', $genre);
                });
            })
            // キーワード検索
            ->when($request->keyword, function ($query, $keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%');
            })
            // 並び替え
            ->when($request->sort, function ($query, $sort) {
                if ($sort == 'random') {
                    // ランダム
                    return $query->inRandomOrder();
                } elseif ($sort == 'rating_desc') {
                    // 評価が高い順（評価がないものは最後に）
                    return $query->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
                        ->selectRaw('restaurants.*, COALESCE(AVG(reviews.rating), 0) as average_rating')
                        ->groupBy('restaurants.id')
                        ->orderByRaw('average_rating DESC, restaurants.id ASC');
                } elseif ($sort == 'rating_asc') {
                    // 評価が低い順（評価がないものは最後に）
                    return $query->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
                        ->selectRaw('restaurants.*, COALESCE(AVG(reviews.rating), 9999) as average_rating')
                        ->groupBy('restaurants.id')
                        ->orderByRaw('average_rating ASC, restaurants.id ASC');
                }
            })
            ->get();

        return view('restaurant-list', compact('restaurants', 'areas', 'genres'));
    }

    // 飲食店詳細取得
    public function showDetail($restaurant_id)
    {
        // 一覧ページで選択した飲食店の詳細情報を取得
        $restaurant = Restaurant::findOrFail($restaurant_id);
        $user = Auth::user();

        // ユーザーの口コミ情報の取得
        $userReview = Review::where('restaurant_id', $restaurant_id)
            ->where('user_id', auth()->id())
            ->first();

        // 他のユーザーの口コミ情報の取得
        $otherReviews = Review::where('restaurant_id', $restaurant_id)
            ->where('user_id', '!=', auth()->id())
            ->get();

        return view('restaurant-detail', compact('restaurant', 'userReview', 'otherReviews'));
    }

    /**
     * CSVファイルからレストラン情報をインポートする
     *
     * @param ImportRestaurantRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(ImportRestaurantRequest $request)
    {
        try {
            // ログインユーザーのIDを取得
            $userId = Auth::id();

            // アップロードされたCSVファイルを取得
            $file = $request->file('csv_file');

            // CSVファイルを読み込む
            $csv = Reader::createFromPath($file->getPathname(), 'r');

            // ヘッダー行を設定
            $csv->setHeaderOffset(0);

            // CSVファイルからレコードを取得
            $records = $csv->getRecords();
            $errors = [];

            // 各レコードを処理
            foreach ($records as $record) {
                // 各レコードのバリデーションを行う
                $validator = Validator::make($record, (new ValidateRestaurantRecord)->rules());

                // バリデーションエラーがあればエラーメッセージを記録し、次のレコードに進む
                if ($validator->fails()) {
                    $errors[] = $validator->errors()->all();
                    continue;
                }

                // レコードのエリア名から対応するエリアを取得
                $area = Area::where('prefectures_name', $record['area'])->first();
                Log::info('Area retrieved: ', ['area' => $area]);

                // レコードのジャンル名から対応するジャンルを取得
                $genre = Genre::where('genre_name', $record['genre'])->first();
                Log::info('Genre retrieved: ', ['genre' => $genre]);

                // エリアまたはジャンルが存在しない場合はエラーメッセージを記録し、次のレコードに進む
                if (!$area || !$genre) {
                    $errors[] = ['エリアまたはジャンルが存在しません: ' . $record['area'] . ', ' . $record['genre']];
                    continue;
                }

                // レストランをデータベースに登録
                Restaurant::create([
                    'user_id' => $userId, // ログインユーザーのIDを提供
                    'area_id' => $area->id,
                    'genre_id' => $genre->id,
                    'name' => $record['name'],
                    'overview' => $record['overview'],
                    'image' => $record['image'],
                ]);
            }

            // エラーがあればエラーメッセージを表示し、なければ成功メッセージを表示
            if (count($errors) > 0) {
                return back()->withErrors($errors);
            }

            // レストラン一覧ページにリダイレクトし、成功メッセージを表示
            return redirect()->route('restaurant.index')->with('success', 'CSVファイルが正常にインポートされました');
        } catch (\Exception $e) {
            // 例外が発生した場合はエラーメッセージを表示
            return back()->withErrors([$e->getMessage()]);
        }
    }
}
