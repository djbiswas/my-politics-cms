<x-app-layout>
    <x-slot name="header">
        <h3>Set Politician Voating Alert</h3>
    </x-slot>

    <div class="generic-form" style="text-align: left;">
        <form class="needs-validation-1" id="validPageForm" method="post" action="{{route('post.pva')}}"   enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$id}}">
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

            <div class="row a-i-center">
                <div class="form-group col-6">
                    <label for="politician_name">Politician Name</label>
                    <input type="text" class="form-control" name='politician_name' id="politician_name" placeholder="Politician Name" value="{{$politician->name}}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <label for="date">Vote Date</label>
                    <input type="date" required class="form-control" name='date' id="date" placeholder="Vote Date" value="">
                </div>
            </div>

            <input type="submit" name='Save' class="btn btn-primary" value="submit"/>
        </form>
    </div>

    @push('scripts')
        <script type="text/javascript">

        </script>
    @endpush
</x-app-layout>
