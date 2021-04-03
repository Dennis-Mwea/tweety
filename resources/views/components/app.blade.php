<x-master>
    <section class="px-8">
        <main class="container mx-auto">
            <div class="lg:flex lg:justify-between">

                @if(auth()->check())
                    <div class="lg:w-32">
                        @include('tweets/_sidebar-links')

                    </div>
                @endif

                <div class="lg:flex-1 lg:mx-10 lg:mb-10" style="max-width: 700px">
                    {{ $slot }}
                </div>

                @if(auth()->check())
                    <div class="lg:w-64">
                        @include('tweets/_friends-list')
                    </div>
                @endif

            </div>
        </main>
    </section>

    <flash message="{{ session('flash') }}"></flash>
</x-master>
