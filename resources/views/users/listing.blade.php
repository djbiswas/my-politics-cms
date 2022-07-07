<x-app-layout>
    <x-slot name="header">
        <h3>Users</h3>
    </x-slot>
    <div class="table-responsive"> 
        <table class="table table-striped data-table" > 
            <thead class="text-primary"> 
                <tr>
                    <th>First Name </th> 
                    <th>Last Name </th> 
                    <th>Email</th> 
                    <th>Phone</th> 
                    <th>Rank</th> 
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
                    ajax: "{{ route('users.index') }}",
                    columns: [
                        {data: 'first_name', name: 'first_name'},
                        {data: 'last_name', name: 'last_name'},
                        {data: 'email', name: 'email'},
                        {data: 'phone', name: 'phone'},
                        {data: 'ranks.title', name: 'ranks.title'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
                
            });
        </script>
    @endpush
</x-app-layout>
