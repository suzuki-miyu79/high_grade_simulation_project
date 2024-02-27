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
    @if (auth()->user()->role === 'admin')
        <li><a href="/admin">AdminPage</a></li>
    @elseif(auth()->user()->role === 'representative')
        <li><a href="/shop-management">ShopManagement</a></li>
    @endif
@endsection
