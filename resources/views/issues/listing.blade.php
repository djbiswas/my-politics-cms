<x-app-layout>
    <x-slot name="header">
        <h3>Politicians</h3>
    </x-slot>
    <div class="table-responsive">
        <table class="table table-striped data-table" >
            <thead class="text-primary">
                <tr>
                    <th> Name </th>
                    <th> User </th>
                    <th>Position</th>
                    <th> Content </th>
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
                    ajax: "{{ route('issues.index') }}",
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'user.first_name', name: 'user.first_name'},
                        {data: 'politician.name', name: 'politician.name'},
                        {data: 'content', name: 'content'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });

            });
        </script>
    @endpush
</x-app-layout>
