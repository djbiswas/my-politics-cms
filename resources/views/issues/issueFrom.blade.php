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

                <div class="form-group col-6">
                    <label for="inputName">Name</label>
                    <input type="text" class="form-control" name='name' id="inputName" placeholder="Name" value="{{ (!empty($data)) ? $data->name : '' }}" required>
                </div>
                <div class="form-group col-6">
                    <label for="politician_id">Politicians</label>
                    <select class="form-control" name='politician_id' id="politician_id" required>
                        <option value="">--Select--</option>
                        @if(!empty($politicians))
                            @foreach ($politicians as $item)
                                <?php
                                    $p_cat_sel = (!empty($data->politician_id) && $data->politician_id == $item['id']) ? 'selected' : '';
                                ?>
                                <option {{$p_cat_sel}} value="{{$item['id']}}">{{$item['name']}}</option>;
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            {{-- <div class="row">
                <div class="form-group col-6">
                    <label for="inputAlias">Alias</label>
                    <input type="text" class="form-control" name='name_alias' id="inpuAlias" placeholder="Alias" value="{{ (!empty($data)) ? $data->name_alias : '' }}">
                </div>
                <div class="form-group col-6">
                    <label for="inputAffiliation">Affiliation</label>
                    <input type="text" class="form-control" name='affiliation' id="inpuAffiliation" placeholder="Affiliation" value="{{ (!empty($data)) ? $data->affiliation : '' }}">
                </div>
            </div> --}}
            <div class="row">
                <div class="form-group col-4">
                    <label for="status">Politicians</label>
                    <select class="form-control" name='status' id="status" required>
                        <option value="">--Select--</option>

                        @if(!empty($status_datas))
                            @foreach ($status_datas as $status_in=>$status_val)
                                <?php
                                    $p_cat_sel = (!empty($data->status) && $data->status == $status_in) ? 'selected' : '';
                                ?>
                                <option {{$p_cat_sel}} value="{{$status_in}}">{{$status_val}}</option>;
                            @endforeach
                        @endif
                    </select>
                </div>


                <div class="form-group quill-field-block col-8">
                    <label for="inputDescription">Description</label>
                    <textarea class="form-control" id="inputDescription" name='content' placeholder="Enter description">{{ (!empty($data)) ? $data->content : '' }}</textarea>
                </div>
            </div>

            <input type="submit" name='Save' class="btn btn-primary" value="save">
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
    @endpush
</x-app-layout>
