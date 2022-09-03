<x-app-layout>
    <x-slot name="header">
        <h3>Politician Voating Alerts</h3>
    </x-slot>

    <div class="table-responsive">
        <table class="table table-striped data-table" >
            <thead class="text-primary">
                <tr>
                    <th> Politician Name </th>
                    <th> Position </th>
                    <th> State </th>
                    <th> Vote Date </th>
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
                    ajax: "{{ route('politician.voting.alerts') }}",
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'position', name: 'position'},
                        {data: 'state', name: 'state'},
                        {data: 'politician_voting_alert.0.date',
                            "searchable": true,
                                "orderable":true,
                                "render": function (data, type, row) {
                                    if (data) {
                                        return data;
                                    }
                                    else {
                                        return '';
                                    }
                            }
                        },
                        // {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
