@extends('layouts.menu')

@section('menu')
    <li><a href="/">Home</a></li>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <li>
            <a href="route('logout')" onclick="event.preventDefault();
                this.closest('form').submit();">
                Logout
            </a>
        </li>
    </form>
    <li><a href="/mypage">Mypage</a></li>
    @if (auth()->user()->isAdmin())
        <li><a href="/admin">AdminPage</a></li>
    @endif
    <li><a href="/shop-management">ShopManagement</a></li>
@endsection
