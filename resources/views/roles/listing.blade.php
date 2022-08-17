<h4>List</h4>
<div class="table-responsive page-user-ranks">
    <table class="table table-striped data-table" >
        <thead class="text-primary">
            <tr>
                <th> Role Title </th>
                <th> Status </th>
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
                ajax: "{{ route('roles.index') }}",
                columns: [
                    {data: 'role', name: 'role'},

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

                    {data: 'action', name: 'action', class:'text-right', orderable: false, searchable: false},
                ]
            });
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush
