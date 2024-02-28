<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AdminMail;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    // 管理者ページ表示
    public function showAdminPage()
    {
        return view('admin-page');
    }

    // メール送信フォーム表示
    public function showMailForm()
    {
        return view('mail.mail');
    }

    // メール送信機能
    public function sendMail(Request $request)
    {
        // フォームから送信されたデータを取得
        $recipientEmail = $request->input('recipient');
        $subject = $request->input('subject');
        $message = $request->input('message');

        try {
            // メール送信処理
            Mail::to($recipientEmail)->send(new AdminMail($subject, $message));

            // メール送信成功時の処理
            return redirect()->back()->with('success', 'メールが正常に送信されました。');
        } catch (\Exception $e) {
            // メール送信失敗時の処理
            return redirect()->back()->with('error', 'メールの送信中にエラーが発生しました。');
        }
    }
}

