<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th width="100px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    @push('scripts')
        <script type="text/javascript">
            $(function () {
                
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('politicians.index') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'name_alias', name: 'name_alias'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
                
            });
        </script>
    @endpush
</x-app-layout>
