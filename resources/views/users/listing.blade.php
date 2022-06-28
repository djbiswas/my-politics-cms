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
                        {data: 'title', name: 'r.title'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
                
            });

            function DeleteFunction(thisVal){
                var form =  $(thisVal).closest("form");
                var name = $(thisVal).data("name");
                event.preventDefault();
                swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                    form.submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
