<x-app-layout>
    <x-slot name="header">
        <h3>Permission Categories</h3>
        <div class="row">
            <div class="col-5">
                @include('permission_categories.addform')
            </div>
            <div class="col-7">
                @include('permission_categories.listing')
            </div>
        </div>
    </x-slot>
</x-app-layout>
