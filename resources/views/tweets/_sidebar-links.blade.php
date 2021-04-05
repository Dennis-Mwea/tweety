<ul class="flex flex-col items-start mb-6">
    <li>
        <a class="font-bold text-lg mb-4 block rounded-full bg-transparent px-2 py-1 hover:bg-blue-500 hover:text-white text-center"
           href="{{ route('home') }}">
            Home
        </a>
    </li>

    <li>
        <a class="font-bold text-lg mb-4 block rounded-full bg-transparent px-2 py-1 hover:bg-blue-500 hover:text-white text-center"
           href="{{ route('explore') }}">
            Explore
        </a>
    </li>

    <notification-link :count="{{ current_user()->unreadNotifications->count() }}"></notification-link>

    <li>
        <a class="font-bold text-lg mb-4 block rounded-full bg-transparent px-2 py-1 hover:bg-blue-500 hover:text-white text-center"
           href="/chats">
            Messages
        </a>
    </li>

    <li>
        <a class="font-bold text-lg mb-4 block rounded-full bg-transparent px-2 py-1 hover:bg-blue-500 hover:text-white text-center"
           href="#">
            Bookmarks
        </a>
    </li>

    <li>
        <a class="font-bold text-lg mb-4 block rounded-full bg-transparent px-2 py-1 hover:bg-blue-500 hover:text-white text-center"
           href="#">
            Lists
        </a>
    </li>

    <li>
        <a class="font-bold text-lg mb-4 block rounded-full bg-transparent px-2 py-1 hover:bg-blue-500 hover:text-white text-center"
           href="{{ route('profile', request()->user()) }}">
            Profile
        </a>
    </li>

    <li>
        <a class="font-bold text-lg mb-4 block rounded-full bg-transparent px-2 py-1 hover:bg-blue-500 hover:text-white text-center"
           href="#">
            More
        </a>
    </li>

    @include('_logout')
</ul>
