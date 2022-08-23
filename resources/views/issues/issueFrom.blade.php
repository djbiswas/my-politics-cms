<x-app-layout>
    <x-slot name="header">
        <h4>{{$data?'Edit' : 'Add'}}  Issue</h4>
    </x-slot>
    <div class="generic-form" style="text-align: left;">
        <form class="needs-validation-1 has-quill-field" id="validPoliticianForm" method="post" action="{{route('post.issue')}}" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">
            @if(!$data)
                <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">
            @endif
            <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}">

            <div class="row">

                <div class="form-group col-4">
                    <label for="inputName">Name</label>
                    <input type="text" class="form-control" name='title' id="inputName" placeholder="Name" value="{{ (!empty($data)) ? $data->title : '' }}" required>
                </div>

                <div class="form-group col-4">
                    {!! Form::label('politician_id', 'Politicians',) !!}
                    {!! Form::select('politician_id', $politicians, $data? $data->politician_id : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird']) !!}
                </div>
                <div class="form-group col-4">
                    {!! Form::label('issue_category_id', 'Select Catagory',) !!}
                    {!! Form::select('issue_category_id', $issueCategories, $data? $data->issue_category_id : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird']) !!}


                </div>
            </div>

            <div class="row">


                <div class="form-group col-4">
                    {!! Form::label('status', 'Status',) !!}
                    {!! Form::select('status', $status_datas, $data? $data->status : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird']) !!}
                </div>


                <div class="form-group quill-field-block col-8">
                    <label for="inputDescription">Description</label>
                    <textarea class="ckeditor" id="inputDescription" name='content' placeholder="Enter description">{{ (!empty($data)) ? $data->content : '' }}</textarea>
                </div>
            </div>

            <input type="submit" name='Save' class="btn btn-primary float-right " value="save">
        </form>
    </div>
    @push('scripts')

        <script>
            $('document').ready(function(){
                $("#validPoliticianForm").validate({
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
                            required:true
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
        <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
        <script>
            $('.ckeditor').ckeditor();
        </script>
    @endpush
</x-app-layout>
