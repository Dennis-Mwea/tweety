<x-master>
    <x-slot name="script">
        <script>
            window.App = {!! json_encode([
                'csrfToken' => csrf_token(),
                'user' => current_user(),
                'signedIn' => auth()->check(),
            ]) !!}
        </script>
    </x-slot>

    <section class="px-8">
        <main class="container mx-auto">
            <div class="lg:flex lg:justify-between">
                @auth
                    <div class="lg:w-64 lg:h-screen lg:fixed lg:left-0 lg:ml-56">
                        @include('tweets/_sidebar-links')

                    </div>
                @endauth

                <div class="lg:flex-1 lg:mx-auto lg:mr-12 lg:mb-12" style="max-width: 700px">
                    {{ $slot }}
                </div>

                @auth
                    <div class="lg:w-64">
                        @include('tweets/_friends-list')
                    </div>
                @endauth
            </div>
        </main>
    </section>

    @if (isset($extra))
        {{ $extra }}
    @endif

    <flash message="{{ session('flash') }}"></flash>
</x-master>
