@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-page.css') }}">
@endsection

@section('content')
    <div class="manager-page__content">
        <p>管理者</p>
        <a class="store-representative_register">店舗代表者アカウント<br>新規作成</a>
    </div>
@endsection
