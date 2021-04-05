<div>
    @forelse($tweets as $tweet)
        <div
            class="flex p-4 hover:bg-gray-200 relative {{ $loop->first ? 'rounded-t-lg' : '' }} {{ $loop->last ? '' :  'border-b border-gray-400'}}">
            <a href="{{ route('tweets.show',$tweet) }}" class="absolute inset-0 w-full h-full">
                @include('tweets._tweet', ['bladeCount' => true])
            </a>
        </div>
    @empty
        <p class="p-4 font-normal">No tweets yet!</p>
    @endforelse

    @if(!is_collection($tweets))
        <div class="flex w-full items-center justify-center">
            {{ $tweets->links() }}
        </div>
    @endif
</div>
