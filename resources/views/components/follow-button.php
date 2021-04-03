<form method="POST" action="/profiles/{{ $user }}/follow">
    @csrf

    <button type="submit" class="bg-blue-400 rounded-full shadow py-2 px-4 text-white text-xs">
        {{ request()->user()->following($user) ? 'Unfollow Me' : 'Follow Me'}}
    </button>
</form>
