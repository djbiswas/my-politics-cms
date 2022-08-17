<div class="generic-form">
    <h4>{{$data?'Edit' : 'Add new'}} role</h4>
    <form class="needs-validation-1" id="validRoleForm" method="post" action="{{route('post.role')}}" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if(!$data)
            {{-- <input type="hidden" name="created_by" value="{{ auth()->user()->id }}"> --}}
        @endif
        {{-- <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}"> --}}
        <input type="hidden" name="id" value="{{$data?$data->id : ''}}">
        <div class="form-group">
            <label for="role">Title</label>
            <input type="text" class="form-control" name='role' id="role" placeholder="Title" value="{{ (!empty($data)) ? $data->role : '' }}" required>
        </div>

        <div class="form-group ">
            {!! Form::label('status', 'Status',) !!}
            {!! Form::select('status', $status_datas, $data? $data->status : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird']) !!}
        </div>


        <input type="submit" name='Save' class="btn btn-primary" />
    </form>
</div>
@push('scripts')
    <script>
        $('document').ready(function(){
            $("#validRoleForm").validate({
                ignore:":not(:visible)",
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error was-validated');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error was-validated');
                },
                errorClass:"error error_preview",
                rules: {
                    role:{
                        required:true,
                        remote: {
                            url: "{{ route('check.role.title') }}",
                            type: "post",
                            beforeSend: function (xhr) { // Handle csrf errors
                                xhr.setRequestHeader('X-CSRF-Token', "{{ csrf_token() }}");
                                xhr.setRequestHeader('role-id', "{{ (!empty($data)) ? $data->id : '' }}");
                            },
                        }
                    },
                },
                messages: {
                    title: {
                        remote: "Title already exist"
                    }
                },
                invalidHandler: function(event, validator) {
                    validator.numberOfInvalids()&&validator.errorList[0].element.focus();
                },
                submitHandler: function (form) {
                    return true;
                }
            });
        });
    </script>
@endpush
