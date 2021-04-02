<div class="border border-gray-300 rounded-lg">
    @foreach($tweets as $tweet)
        @include('tweets/_tweet')
    @endforeach
</div>
