<x-app-layout>
    <x-slot name="header">
        <h4>{{$data?'Edit' : 'Add'}}  Post Data</h4>
    </x-slot>
    <div class="generic-form" style="text-align: left;">
        <form class="needs-validation-1" action="{{route('post.post')}}" method="post" id="validUserForm" name="form_01" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

            <div class="row a-i-center">
                <div class="form-group col-3">
                    <label for="">Position Name</label>
                    <input type="text"  class="form-control" name='' id="position_name" placeholder="Position Name" value="{{ (!empty($data)) ? $data->politician->name : '' }}" readonly>
                </div>
                <div class="form-group col-3 user-phone">
                    <label for="phone">User Name</label>
                    <input type="text" <?php echo isset($_GET['id'])?'readonly':'required'; ?> class="form-control" name='' id="phone" placeholder="User Name" value="{{ (!empty($data)) ? $data->user->name : '' }}" readonly >
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" name='content' placeholder="Enter Content">{{ (!empty($data)) ? $data->content : '' }}</textarea>

                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                        <div class="display_status">Status</div>
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

        <script>

        </script>
    @endpush
</x-app-layout>
