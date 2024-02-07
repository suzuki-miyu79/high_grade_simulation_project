@extends('layouts.menu')

@section('menu1')
    <li><a href="/">Home</a></li>
@endsection

@section('menu2')
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <li>
            <a href="route('logout')" onclick="event.preventDefault();
                this.closest('form').submit();">
                Logout
            </a>
        </li>
    </form>
@endsection

@section('menu3')
    <li><a href="/mypage">Mypage</a></li>
@endsection
