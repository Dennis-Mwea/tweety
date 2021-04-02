@extends('layouts.app')

@section('content')
    @include('tweets/_publish-tweet-panel')

    <div class="border border-gray-300 rounded-lg">
        @foreach($tweets as $tweet)
            @include('tweets/_tweet')
        @endforeach
    </div>
@endsection
