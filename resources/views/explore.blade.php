<x-app>
    <div>
        @include('follows._user-list')

        {{ $users->links() }}
    </div>
</x-app>
