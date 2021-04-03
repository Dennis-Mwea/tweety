<x-app>
    <publish-tweet-panel :user="{{ auth()->user()}}"></publish-tweet-panel>

    <div class="border border-gray-300 rounded-lg">
        @include('_timeline')
    </div>
</x-app>
