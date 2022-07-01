<div class="generic-form">
    <?php
    $edit_data = $sub_action = $sub_btn_text = "";
    ?>
    <h4>{{$data?'Edit' : 'Add'}} new category</h4>
    <form class="needs-validation-1" id="validRankForm" method="post" action="{{route('post.category')}}" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if(!$data) 
            <input type="hidden" name="created_by" value="{{ auth()->user()->id }}"> 
        @endif
        <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}">
        <input type="hidden" name="id" value="{{$data?$data->id : ''}}">
        <div class="form-group">
            <label for="inputTitle">Name</label>
            <input type="text" class="form-control" name='name' id="inputTitle" placeholder="Name" value="{{ (!empty($data)) ? $data->name : '' }}" required>
        </div>
        <div class="form-group">
            <label for="inputtexteditor">Description</label>
            <textarea class="form-control" name="description" id="textareaDescription" placeholder="Description">{{ (!empty($data)) ? $data->description : '' }}</textarea>
        </div>
        <div class="form-group-two">
            <label for="formFile" class="form-label">Icon</label>
            <x-display-image :data="$data?$data : ''" :fileName="'icon'"></x-display-image>
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
                            url: "{{ route('check.category.name') }}",
                            type: "post",
                            beforeSend: function (xhr) { // Handle csrf errors
                                xhr.setRequestHeader('X-CSRF-Token', "{{ csrf_token() }}");
                                xhr.setRequestHeader('category-id', "{{ (!empty($data)) ? $data->id : '' }}");
                            },
                        }
                    },
                },
                messages: {
                    name: {
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