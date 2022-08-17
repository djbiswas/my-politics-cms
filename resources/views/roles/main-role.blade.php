<x-app-layout>
    <x-slot name="header">
        <h3>Roles</h3>
        <div class="row">
            <div class="col-5">
                @include('roles.addform')
            </div>
            <div class="col-1">
            </div>
            <div class="col-6">
                @include('roles.listing')
            </div>
        </div>
    </x-slot>
</x-app-layout>
