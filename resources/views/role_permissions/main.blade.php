<x-app-layout>
    <x-slot name="header">
        <h3>Permission </h3>
        <hr>
        <div class="row">
            <div class="col-5">
                @include('role_permissions.addform')
            </div>
            <div class="col-7">
                @include('role_permissions.listing')
            </div>
        </div>
    </x-slot>
</x-app-layout>
