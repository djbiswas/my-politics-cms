<x-app-layout>
    <x-slot name="header">
        <h3>Politicians</h3>
    </x-slot>
    <div class="table-responsive">
        <table class="table table-striped data-table" >
            <thead class="text-primary">
                <tr>
                    <th> Name </th>
                    <th> Alias </th>
                    <th> Politician</th>
                    <th> Last Updated </th>
                    <th class="text-right"> Action </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $(function () {

                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('politicians.index') }}",
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'name_alias', name: 'name_alias'},
                        {data: 'position', name: 'position'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });

            });
        </script>
    @endpush
</x-app-layout>
