<div class="generic-form">
    <?php
    $edit_data = $sub_action = $sub_btn_text = "";
    ?>
    <h4>{{$data?'Edit' : 'Add'}} new permission category</h4>


        {!! Form::open(['route' => 'post.permission_category', 'class' =>'needs-validation-1', 'id' => 'validRankForm','enctype' =>'multipart/form-data', 'novalidate'])  !!}

        <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

        <div class="form-group">
            {!! Form::label('name', 'Name',) !!}
            {!! Form::text('name', $data? $data->name : null, ['class'=>'form-control', 'placeholder'=>'Name']) !!}
        </div>

        <input type="submit" name='Save' class="btn btn-primary" />
    </form>
</div>

@push('scripts')
    <script>
        $('document').ready(function(){
            $("#validRankForm").validate({
                ignore:":not(:visible)",
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error was-validated');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error was-validated');
                },
                errorClass:"error error_preview",
                rules: {
                    name:{
                        required:true,
                        remote: {
                            url: "{{ route('check.permission_category.name') }}",
                            type: "post",
                            beforeSend: function (xhr) { // Handle csrf errors
                                xhr.setRequestHeader('X-CSRF-Token', "{{ csrf_token() }}");
                                xhr.setRequestHeader('permissionCategory-id', "{{ (!empty($data)) ? $data->id : '' }}");
                            },
                        }
                    },
                },
                messages: {
                    title: {
                        remote: "Name already exist"
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
