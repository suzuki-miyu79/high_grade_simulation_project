@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mail.css') }}">
@endsection

@section('content')
    <div class="mail__content">
        <h2 class="mail__content-title">メール送信フォーム</h2>
        <form action="{{ route('send.mail') }}" method="POST" class="mail__content__form">
            @csrf
            <div class="mail__content__form-recipient">
                <label for="recipient">宛先:</label>
                <input type="email" id="recipient" name="recipient" required>
            </div>
            <div class="mail__content__form-subject">
                <label for="subject">件名:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="mail__content__form-text">
                <label for="message">メッセージ:</label>
                <textarea id="message" name="message" cols="30" rows="10" required></textarea>
            </div>
            <div class="mail__content__form-button">
                <button class="mail__content__form-button-submit">送信</button>
            </div>
        </form>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endsection
