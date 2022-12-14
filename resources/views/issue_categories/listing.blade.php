<h4>List</h4>
<div class="table-responsive page-user-ranks">
    <table class="table table-striped data-table" >
        <thead class="text-primary">
            <tr>
                <th> Name </th>
                <th> Status </th>
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
                ajax: "{{ route('issue_categories.index') }}",
                columns: [
                    {data: 'title', name: 'title'},
                    {data: "status",
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
                    {data: 'action', name: 'action', class:'text-right', orderable: false, searchable: false},
                ]
            });
            // $('.ckeditor').ckeditor();
        });
    </script>
@endpush
