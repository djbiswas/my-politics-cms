@php
use Illuminate\Support\Str;
@endphp
<div class="generic-form">
    <h4>{{$data?'Edit' : 'Add new'}} rank</h4>
    <form class="needs-validation-1" id="validRankForm" method="post" action="{{route('post.rank')}}" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if(!$data) 
            <input type="hidden" name="created_by" value="{{ auth()->user()->id }}"> 
        @endif
        <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}">
        <input type="hidden" name="id" value="{{$data?$data->id : ''}}">
        <div class="form-group">
            <label for="inputTitle">Title</label>
            <input type="text" class="form-control" name='title' id="title" placeholder="Title" value="{{ (!empty($data)) ? $data->title : '' }}" required>
        </div>
        <div class="form-group-two">
            <label for="formFile" class="form-label">Image</label>
                @if($data && !Str::contains($data->image, 'text=Default'))
                    <div class="p-image-sec">
                        <div class="p-image-container">
                            <img class="p-image" src="{{asset($data->image)}}"/>
                            <button type="button" class="close btn-img-clear" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="hidden" class="existing_image" name="image_existing" value="1" />
                        <input class="form-control form-file" type="file" id="formFile" name='image'>
                        <input value="{{asset($data->image)}}" type="hidden" name='ex_img_path'>
                    </div>
                @else
                    <input class="form-control" type="file" id="formFile" name='image'>
                @endif
        </div>
        <div class="form-group">
            <label for="inputPostcount">Number of Post Count(to qualify this Rank)</label>
            <br>
            <small style="font-weight:bold;">Please enter the minimum count to qualify for this Rank. </small>
            <input type="number" class="form-control" name='post_count' id="inputPostcount" placeholder="Number of Post Count" value="{{ (!empty($data)) ? $data->post_count : '' }}" required>
        </div>
        <div class="form-group">
            <label for="inputTrustpercentage">Number of Trust Percentage</label><br>
            <small style="font-weight:bold;">Please enter the minimum count of percentage to qualify for this Rank. </small>
            <input type="number" class="form-control" name='trust_percentage' id="inputTrustpercentage" placeholder="Number of Trust Percentage" value="{{ (!empty($data)) ? $data->trust_percentage : '' }}" required>
        </div>
        <div class="form-group">
            <label for="inputtexteditor">Description</label>
            <textarea id='long_desc' class="ckeditor" name='long_desc' >{{ (!empty($data)) ? $data->long_desc : '' }}</textarea><br>
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
                            url: "{{ route('check.rank.title') }}",
                            type: "post",
                            beforeSend: function (xhr) { // Handle csrf errors
                                xhr.setRequestHeader('X-CSRF-Token', "{{ csrf_token() }}");
                                xhr.setRequestHeader('rank-id', "{{ (!empty($data)) ? $data->id : '' }}");
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