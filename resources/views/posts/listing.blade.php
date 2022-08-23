<x-app-layout>
    <x-slot name="header">
        <h3>Posts</h3>
    </x-slot>
    <div class="table-responsive">
        <table class="table table-striped data-table" >
            <thead class="text-primary">
                <tr>
                    <th>Position</th>
                    <th>User</th>
                    <th>Flagged</th>
                    <th>Last Updated </th>
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
                    ajax: "{{ route('posts.index') }}",
                    columns: [
                        {data: 'politician.name', name: 'politician.name'},
                        {data: 'user.first_name', name: 'user.first_name'},
                        {data: "status",
                            "searchable": true,
                            "orderable":true,
                            "render": function (data, type, row) {
                                if (data == '1') {
                                    return 'Active';
                                }
                                else {
                                    return 'InActive';
                                }
                            }
                        },
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
