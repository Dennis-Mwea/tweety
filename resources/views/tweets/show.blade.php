<x-app>
    <div class="flex p-4 border border-gray-300 rounded-lg">
        @include("tweets._tweet")
    </div>

    <hr class="mb-6 mt-6">

    <h3 class="text-lg font-bold mb-6"> Replies</h3>

    <div>
        @include('replies.list')
    </div>

    <x-slot name="extra">
        <portal-target name="add-reply" slim></portal-target>
    </x-slot>
</x-app>
