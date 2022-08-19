<x-app-layout>
    <x-slot name="header">
        <h4>{{$data?'Edit' : 'Add'}}  User Roles</h4>
    </x-slot>
    <div class="generic-form" style="text-align: left;">
        <form class="needs-validation-1" id="validPageForm" method="post" action="{{route('post.page')}}" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            {{-- <input type="hidden" name="id" value="{{$data?$data->id : ''}}"> --}}
            <input type="hidden" name="role_id" value="{{$data?$role_id : ''}}">
            <div class="row a-i-center">
                <div class="col-6">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="page_name">Role Name</label>
                            <input type="text" required class="form-control" name='role_name' id="page_name" placeholder="Page Name" value="{{$data?$role_name : ''}}" readonly>
                        </div>
                    </div>

                    @php $checkbox_auto_id = 1; @endphp
                    <div class="row">
                        <div class="col-12">
                            @foreach ($data as $permission)
                                <div class="form-group col-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="permission_id[]" class="form-check-input checkfix" id="permission_id_{{$checkbox_auto_id}}" value="{{$permission->id}}">
                                        <label class="form-check-label" for="permission_id_{{$checkbox_auto_id}}">{{$permission->permission->name}}</label>
                                    </div>
                                </div>
                                @php $checkbox_auto_id = $checkbox_auto_id + 1; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <input type="submit" name='Save' class="btn btn-primary" />
        </form>
    </div>

    @push('scripts')
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

    <script>
        $('document').ready(function(){
            $("#validPageForm").validate({
                ignore:":not(:visible)",
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error was-validated');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error was-validated');
                },
                errorClass:"error error_preview",
                rules: {
                    page_name:{
                        required:true,
                        remote: {
                            url: "{{ route('check.page.name') }}",
                            type: "post",
                            beforeSend: function (xhr) { // Handle csrf errors
                                xhr.setRequestHeader('X-CSRF-Token', "{{ csrf_token() }}");
                                xhr.setRequestHeader('page-id', "{{ (!empty($data)) ? $role_id : '' }}");
                            },
                        }
                    },
                },
                messages: {
                    page_name: {
                        remote: "Page name already exist"
                    }
                },
                invalidHandler: function(event, validator) {
                    validator.numberOfInvalids()&&validator.errorList[0].element.focus();
                },
                submitHandler: function (form) {
                    return true;
                }
            });
            $('.ckeditor').ckeditor();

        });
    </script>
@endpush
</x-app-layout>
