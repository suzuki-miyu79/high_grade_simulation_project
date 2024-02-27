<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AdminMail;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function showAdminPage()
    {
        return view('admin-page');
    }

    // メール送信機能
    public function sendMail()
    {
        try {
            // メール送信処理
            $email = 'authentication.laravel@gmail.com';
            Mail::to($email)->send(new AdminMail());

            // メール送信成功時の処理
            return redirect()->back()->with('success', 'メールが正常に送信されました。');
        } catch (\Exception $e) {
            // メール送信失敗時の処理
            return redirect()->back()->with('error', 'メールの送信中にエラーが発生しました。');
        }
    }
}

