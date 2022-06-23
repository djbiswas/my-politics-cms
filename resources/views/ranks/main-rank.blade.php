<x-app-layout>
    <x-slot name="header">
        <h3>Ranks</h3>
        <div class="row">
            <div class="col-5">
                @include('ranks.addform')
            </div>
            <div class="col-1">
            </div>
            <div class="col-6">
                @include('ranks.listing')
            </div>
        </div>
    </x-slot>
</x-app-layout>
