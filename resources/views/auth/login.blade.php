@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div class="login__content">
        <div class="login__heading">
            <h2>Login</h2>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="login-form">
                <div class="login-form__input">
                    <img src="image/mail-icon.png" alt="">
                    <input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="Email"
                        :value="old('email')" required autofocus autocomplete="username" />
                </div>
                <div class="login-form__error">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="login-form__input">
                    <img src="image/lock-icon.png" alt="">
                    <input id="password" class="block mt-1 w-full" placeholder="Password" type="password" name="password"
                        required autocomplete="current-password" />
                </div>
                <div class="login-form__error">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="login-form__button">
                    <button class="login-form__button-submit">ログイン</button>
                </div>
            </div>
        </form>
    </div>
@endsection
