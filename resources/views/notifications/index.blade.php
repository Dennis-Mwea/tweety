<x-app>
    @forelse($notifications as $notification)
        <div class="flex bg-white shadow p-4 rounded-xl mb-4 border border-gray-300">
            <div class="flex w-full">
                @if ($notification->type === 'App\Notifications\YouWereFollowed')
                    @include('notifications._youwerefollowed')
                @else
                    @include('notifications._youwerementioned')
                @endif
                <div class="flex-1 text-right tracking-tight">
                    <h3 class="font-semibold broder border-r-4 border-blue-400 p-2 rounded">{{ $notification->created_at->format('M d') }} </h3>
                    <span
                        class="text-gray-700 text-sm font-bold">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    @empty
        <p>You have no notifications at the moment.</p>
    @endforelse
</x-app>
