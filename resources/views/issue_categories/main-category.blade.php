<x-app-layout>
    <x-slot name="header">
        <h3>Issue Categories</h3>
        <div class="row">
            <div class="col-5">
                @include('issue_categories.addform')
            </div>
            <div class="col-7">
                @include('issue_categories.listing')
            </div>
        </div>
    </x-slot>
</x-app-layout>
