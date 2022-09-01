<x-app-layout>
    <x-slot name="header">
        <h3>Politician Voating Alerts</h3>
    </x-slot>
    <div class="table-responsive">
        <table class="table table-striped data-table" >
            <thead class="text-primary">
                <tr>
                    <th> Politician Name </th>
                    <th> User Name </th>
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
                        {data: 'politician.name', name: 'politician.name'},
                        {data: 'user.name', name: 'user.name'},
                        {data: 'date', name: 'date'},
                        // {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });

            });
        </script>
    @endpush
</x-app-layout>
