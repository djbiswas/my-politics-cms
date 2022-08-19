<h4>List</h4>
<div class="table-responsive page-user-ranks">
    <table class="table table-striped data-table" >
        <thead class="text-primary">
            <tr>
                <th> Name </th>
                <th> Permission Category </th>
                <th> Slug </th>
                <th> Route Name </th>
                <th> Last Updated </th>
                <th class="text-right"> Action </th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@push('scripts')
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('permissions.index') }}",
                columns: [
                        {data: 'name', name: 'name'},
                        {data: 'permission_category_id', name: 'permission_category_id'},
                        {data: 'slug', name: 'slug'},
                        {data: 'route.name', name: 'route.name'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
            });
            // $('.ckeditor').ckeditor();
        });
    </script>
@endpush
