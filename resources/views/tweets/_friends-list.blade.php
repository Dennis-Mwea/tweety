<h3 class="font-bold text-xl mb-4">Following</h3>

<ul>
    @foreach(request()->user()->follows as $user)
        <li class="mb-4">
            <div>
                <a href="{{ $user->name }}" class="flex items-center text-sm">
                    <img src="{{ $user->avatar }}" alt="" class="rounded-full mr-2" width="40" height="40">

                    {{ $user->name }}
                </a>
            </div>
        </li>
    @endforeach
</ul>
