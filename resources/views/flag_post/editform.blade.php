<x-app-layout>
    <x-slot name="header">
        <h4>{{$data?'Edit' : 'Add'}}  Post Flagged Data</h4>
    </x-slot>
    <div class="generic-form" style="text-align: left;">
        <form class="needs-validation-1" action="{{route('post.flag')}}" method="post" id="validUserForm" name="form_01" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

            <div class="row a-i-center">
                <div class="form-group col-3">
                    <label for="">Post Politician Name</label>
                    <input type="text"  class="form-control" name='' id="position_name" placeholder="Position Name" value="{{ (!empty($data)) ? $data->politician->name : '' }}" readonly>
                </div>
                <div class="form-group col-3">
                    <label for="">Post Flagged User Name</label>
                    <input type="text"  class="form-control" name='' id="position_name" placeholder="Position Name" value="{{ (!empty($data)) ? $data->user->first_name : '' }}" readonly>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label for="content">Post Content</label>
                    <textarea class="ckeditor" id="content" name='content' placeholder="Enter Content">{{ (!empty($data)) ? $data->post->content : '' }}</textarea>

                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                        <div class="display_status">Flagged Status</div>
                        <div class="form-group">
                            <input type="radio" name='status' id="radioButton1" value="1" {{(empty($data) || (!empty($data) && $data->status == 1)) ? 'checked' : ''}} >
                            <label for="radioButton1">Active</label>
                            <input type="radio" name='status' id="radioButton2" value="0" {{((!empty($data) && $data->status == 0)) ? 'checked' : ''}} >
                            <label for="radioButton2">Inactive</label>
                        </div>
                </div>
            </div>


            <?php
            if (isset($_GET['id'])) {
                echo '<input type="hidden" name="id" value="' . $_GET['id'] . '" />';
            }
            ?>
            <input type="submit" name='Save' class="btn btn-primary"  value="Save">
        </form>
    </div>
    @push('scripts')
        <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
        <script>
            $('.ckeditor').ckeditor();
        </script>
    @endpush
</x-app-layout>
