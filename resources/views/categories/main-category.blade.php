<x-app-layout>
    <x-slot name="header">
        <h3>Categories</h3>
        <div class="row">
            <div class="col-5">
                @include('categories.addform')
            </div>
            <div class="col-7">
                @include('categories.listing')
            </div>
        </div>
    </x-slot>
</x-app-layout>
