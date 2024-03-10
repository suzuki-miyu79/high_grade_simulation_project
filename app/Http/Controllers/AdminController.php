<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AdminMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    // 管理者ページ表示
    public function showAdminPage()
    {
        return view('admin-page');
    }

    // 店舗代表者登録ページ表示
    public function showRepresentativeRegister()
    {
        return view('representative-register');
    }

    // 店舗代表者登録機能
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:191', 'unique:' . User::class],
            'password' => ['required', 'min:8', 'max:191', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'role' => 'representative',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('registered.show');
    }

    // 登録完了ページ表示
    public function showRegistered()
    {
        return view('registered');
    }

    // メール送信フォーム表示
    public function showMailForm()
    {
        // ユーザーのリストを取得
        $users = User::all();

        return view('mail', compact('users'));
    }

    // メール送信機能
    public function sendMail(Request $request)
    {
        // フォームから送信された、選択されたユーザーのメールアドレスと名前を取得
        $selectedRecipients = $request->input('recipients');
        $users = User::whereIn('email', $selectedRecipients)->select('name', 'email')->get();

        // フォームから送信されたデータを取得
        $message = nl2br($request->input('message'));

        foreach ($users as $user) {
            try {
                // メール送信処理
                Mail::to($user->email)->send(new AdminMail($user->name, $message));
            } catch (\Exception $e) {
                // メール送信失敗時にログを記録する
                Log::error('メールの送信中にエラーが発生しました。', ['exception' => $e]);
                // メール送信失敗時の処理
                return redirect()->back()->with('error', 'メールの送信中にエラーが発生しました。');
            }
        }

        // メール送信成功時の処理
        return redirect()->back()->with('success', 'メールが送信されました。');
    }
}

