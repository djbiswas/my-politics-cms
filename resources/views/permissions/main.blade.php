<x-app-layout>
    <x-slot name="header">
        <h3>Permissions </h3>
        <hr>
        <div class="row">
            <div class="col-5">
                @include('permissions.addform')
            </div>
            <div class="col-7">
                @include('permissions.listing')
            </div>
        </div>
    </x-slot>
</x-app-layout>
