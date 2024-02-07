@extends('layouts.app')

@section('content')
    <div id="app">
        <search-component :areas="{{ json_encode($areas) }}" :genres="{{ json_encode($genres) }}"></search-component>

        <!-- 他のコンテンツをここに追加する -->
    </div>
@endsection
