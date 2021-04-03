<div class="flex p-4 {{ $loop->last ? '' :  'border-b border-gray-400'}}">
    <div class="mr-2 flex-shrink-0">
        <a href="{{ route('profile', $tweet->user) }}">
            <img src="{{ $tweet->user->avatar }}" alt="" class="rounded-full mr-2" width="50" height="50">
        </a>
    </div>

    <div class="flex-1">
        <a href="{{ route('profile',$tweet->user) }}">
            <h5 class="font-bold mb-4">{{ $tweet->user->name }}</h5>
        </a>

        <p class="text-sm">
            {{ $tweet->body }}
        </p>

        <like-buttons :tweet="{{ $tweet }}"></like-buttons>
    </div>

    <div>
        @can('edit',$tweet->user)
            <dropdown align="right" width="200px">
                <template v-slot:trigger>
                    <button
                        class="flex items-center text-default no-underline text-sm focus:outline-none"
                        v-pre
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 text-gray-700">
                            <path fill="currentColor"
                                  d="M15.3 9.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"/>
                        </svg>

                    </button>
                </template>

                <button type="submit" class="px-2 py-2 w-full text-left class text-red-500 hover:underline"
                        @click.prevent="$modal.show('confirm-delete',{'id':{{$tweet->id }}})">
                    Delete
                </button>
            </dropdown>
        @endcan
    </div>

    @can('edit',$tweet->user)
        <confirm-delete-modal></confirm-delete-modal>
    @endcan
</div>
