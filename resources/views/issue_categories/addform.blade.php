<div class="generic-form">
    <?php
    $edit_data = $sub_action = $sub_btn_text = "";
    ?>
    <h4>{{$data?'Edit' : 'Add'}} new category</h4>


    {{-- <form class="needs-validation-1" id="validRankForm" method="post" action="{{route('post.issue_category')}}" enctype="multipart/form-data" novalidate> --}}

        {!! Form::open(['route' => 'post.issue_category', 'class' =>'needs-validation-1', 'id' => 'validRankForm','enctype' =>'multipart/form-data', 'novalidate'])  !!}


        <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

        <div class="form-group">
            {!! Form::label('title', 'Title',) !!}
            {!! Form::text('title', $data? $data->title : null, ['class'=>'form-control', 'placeholder'=>'Title']) !!}
        </div>

        <div class="form-group ">
            {!! Form::label('status', 'Status',) !!}
            {!! Form::select('status', $status_datas, $data? $data->status : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird']) !!}

        </div>

        <div class="form-group ">
            {!! Form::label('image', 'Select Icon',) !!}
            {!! Form::select('image', $heroicons, $data? $data->image : null, ['class' => 'form-control select2img', 'placeholder' => '--Select--', 'requird']) !!}
        </div>


        {{-- <div class="form-group-two">
            <label for="formFile" class="form-label">Image</label>
            <x-display-image :data="$data?$data : ''" :fileName="'image'"></x-display-image>
        </div> --}}
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
                                xhr.setRequestHeader('issueCategory-id', "{{ (!empty($data)) ? $data->id : '' }}");
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
