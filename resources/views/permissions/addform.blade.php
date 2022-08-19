<div class="generic-form">
    <?php
    $edit_data = $sub_action = $sub_btn_text = "";
    ?>
    <h4>{{$data?'Edit' : 'Add'}} permission</h4>

        {!! Form::open(['route' => 'post.permission', 'class' =>'needs-validation-1', 'id' => 'validRankForm','enctype' =>'multipart/form-data', 'novalidate'])  !!}


        <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

        <div class="form-group">
            {!! Form::label('name', 'Name',) !!}
            {!! Form::text('name', $data? $data->name : null, ['class'=>'form-control', 'placeholder'=>'Name']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('permission_category_id', 'Select Permission Category',) !!}
            {!! Form::select('permission_category_id', $permission_categoris, $data? $data->permission_category_id : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('slug', 'Slug',) !!}
            {!! Form::text('slug', $data? $data->slug : null, ['class'=>'form-control', 'placeholder'=>'Slug']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('route_name', 'Route Name',) !!}
            {!! Form::text('route_name', $data? $data->route_name : null, ['class'=>'form-control', 'placeholder'=>'Route Name']) !!}
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
                    title:{
                        required:true,
                        remote: {
                            url: "{{ route('check.issue_category.name') }}",
                            type: "post",
                            beforeSend: function (xhr) { // Handle csrf errors
                                xhr.setRequestHeader('X-CSRF-Token', "{{ csrf_token() }}");
                                xhr.setRequestHeader('permission-id', "{{ (!empty($data)) ? $data->id : '' }}");
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
