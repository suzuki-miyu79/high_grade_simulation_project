@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div class="register__content">
        <div class="register__heading">
            <h2>Registration</h2>
        </div>
        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf
            <div class="register-form__input">
                <img src="image/character-icon.png" alt="">
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" placeholder="Username"
                    :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <div class="register-form__error">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="register-form__input">
                <img src="image/mail-icon.png" alt="">
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="Email"
                    :value="old('email')" required autocomplete="username" />
            </div>
            <div class="register-form__error">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="register-form__input">
                <img src="image/lock-icon.png" alt="">
                <x-text-input id="password" class="block mt-1 w-full" placeholder="Password" type="password"
                    name="password" required autocomplete="new-password" />
            </div>
            <div class="register-form__error">
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="register-form__button">
                <button class="register-form__button-submit">登録</button>
            </div>
        </form>
    </div>
@endsection
