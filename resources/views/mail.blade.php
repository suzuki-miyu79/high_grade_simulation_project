@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mail.css') }}">
@endsection

@section('content')
    <div class="mail__content">
        <h2 class="mail__content-title">メール送信フォーム</h2>
        <div class="mail__content-form">
            <form action="{{ route('send.mail') }}" method="POST" class="mail__content__form">
                @csrf
                <div class="mail__content__form-recipient">
                    <label for="recipient">宛先:</label>
                    @foreach ($users as $user)
                        <div class="mail__content__form-recipient-item">
                            <input type="checkbox" id="recipient_{{ $user->id }}" name="recipients[]"
                                value="{{ $user->email }}" checked>
                            <label for="recipient_{{ $user->id }}">{{ $user->name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="mail__content__form-text">
                    <label for="message">本文:</label>
                    <textarea id="message" name="message" rows="15" required></textarea>
                </div>
                <div class="mail__content__form-button">
                    <button class="mail__content__form-button-submit">送信</button>
                </div>
            </form>
        </div>
    </div>
@endsection
