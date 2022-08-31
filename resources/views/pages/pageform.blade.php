<x-app-layout>
    <x-slot name="header">
        <h4>{{$data?'Edit' : 'Add'}}  Page</h4>
    </x-slot>
    <div class="generic-form" style="text-align: left;">
        <form class="needs-validation-1" id="validPageForm" method="post" action="{{route('post.page')}}"   enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">
            <div class="row a-i-center">
                <div class="form-group col-6">
                    <label for="page_name">Page Name</label>
                    <input type="text" required class="form-control" name='page_name' id="page_name" placeholder="Page Name" value="{{$data?$data->page_name : ''}}">
                </div>
                <div class="form-group col-6">
                    <label for="page_url">Page URL</label>
                    <input type="text" required class="form-control" name='page_url' id="page_url" placeholder="Page URL" value="{{$data?$data->page_url : ''}}">
                </div>

                <div class="form-group-two col-6 mb-3">
                    <label for="cover" class="form-label">Page Cover</label>
                    <x-display-image :data="$data?$data : ''" :fileName="'cover'" ></x-display-image>
                </div>

                <div class=" col-12">
                    <div class="form-group">
                        <label for="inputtexteditor">Page Content</label>
                        <textarea required class="ckeditor" id='page_content' name='page_content' >{{$data?$data->page_content : ''}}</textarea><br>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class=" col-6">
                    <div class="display_status">Display Status</div>
                    <div class="form-group">
                        <input type="radio" name='status' id="radioButton1" value="1" {{(empty($data) || (!empty($data) && $data->status == 1)) ? 'checked' : ''}} >
                        <label for="radioButton1">Active</label>
                        <input type="radio" name='status' id="radioButton2" value="0" {{((!empty($data) && $data->status == 0)) ? 'checked' : ''}} >
                        <label for="radioButton2">Inactive</label>
                    </div>
                </div>
            </div>
            <input type="submit" name='Save' class="btn btn-primary" value="submit"/>
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
                                xhr.setRequestHeader('page-id', "{{ (!empty($data)) ? $data->id : '' }}");
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
