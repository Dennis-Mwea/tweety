<div class="border border-blue-400 rounded-lg py-6 px-8 mb-8">
    <form action="{{ route('createTweet') }}" method="post">
        @csrf

        <textarea name="body" class="w-full focus:outline-none focus:placeholder-gray-700 border-0 focus:border-0"
                  placeholder="What's up doc?"></textarea>

        <hr class="mb-4">

        <footer class="flex justify-between">
            <img src="{{ request()->user()->avatar }}" alt="Your avatar" class="rounded-full mr-2" width="50"
                 height="50">

            <button type="submit" class="bg-blue-500 rounded-full shadow py-2 px-3 text-white">Tweet-a-roo!</button>
        </footer>
    </form>
</div>
