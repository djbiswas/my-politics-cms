<x-app-layout>
    <x-slot name="header">
        <h3>Pages</h3>
    </x-slot>
    <div class="table-responsive"> 
        <table class="table table-striped data-table" > 
            <thead class="text-primary"> 
                <tr>
                    <th>Page Name </th> 
                    <th>Page URL </th> 
                    <th>Display Status</th>  
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
                    ajax: "{{ route('pages.index') }}",
                    columns: [
                        {data: 'page_name', name: 'page_name'},
                        {data: 'page_url', name: 'page_url'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
                
            });
        </script>
    @endpush
</x-app-layout>
