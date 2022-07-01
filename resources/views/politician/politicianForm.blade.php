<x-app-layout>
    <x-slot name="header">
        <h4>{{$data?'Edit' : 'Add'}}  User</h4>
    </x-slot>
    <div class="generic-form" style="text-align: left;">
        <form class="needs-validation-1 has-quill-field" id="validPoliticianForm" method="post" action="{{route('post.politician')}}" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">
            <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" class="form-control" name='name' id="inputName" placeholder="Name" value="{{ (!empty($data)) ? $data->name : '' }}" required>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <label for="inputTitle">Title</label>
                    <input type="text" class="form-control" name='title' id="inpuTitle" placeholder="Title" value="{{ (!empty($data)) ? $data->title : '' }}">
                </div>
                <div class="form-group col-6">
                    <label for="selectCategory">Category</label>
                    <select class="form-control" name='meta[p_cat]' id="selectCategory" required>
                        <option>--Select--</option>
                        @if(!empty($categories))
                            @foreach ($categories as $item)
                                <?php
                                    $p_cat_sel = (!empty($meta_data) && $meta_data['p_cat'] == $item['id']) ? 'selected' : '';
                                ?>
                                <option {{$p_cat_sel}} value="{{$item['id']}}">{{$item['name']}}</option>;
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <label for="inputAlias">Alias</label>
                    <input type="text" class="form-control" name='name_alias' id="inpuAlias" placeholder="Alias" value="{{ (!empty($data)) ? $data->name_alias : '' }}">
                </div>
                <div class="form-group col-6">
                    <label for="inputAffiliation">Affiliation</label>
                    <input type="text" class="form-control" name='affiliation' id="inpuAffiliation" placeholder="Affiliation" value="{{ (!empty($data)) ? $data->affiliation : '' }}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <label for="inputPosition">Position</label>
                    <input type="text" class="form-control" name='position' id="inputPosition" placeholder="Position" value="{{ (!empty($data)) ? $data->position : '' }}">
                </div>
                <div class="form-group col-6">
                    <label for="inputTwitterFeed">Twitter feed url</label>
                    <input type="url" class="form-control" name='meta[twitter_feed]' id="inputTwitterFeed" placeholder="URL" value="{{ (!empty($metaData['twitter_feed'])) ? $metaData['twitter_feed'] : '' }}">
                </div>
            </div>
            <div class="form-group quill-field-block">
                <label for="inputDescription">Description</label>
                <textarea class="form-control" id="inputDescription" name='politician_description' placeholder="Enter description">{{ (!empty($data)) ? $data->politician_description : '' }}</textarea>
            </div>
            <div class="row">
                <div class="form-group col-4">
                    <label for="voteByDate">Vote by date</label>
                    <input type="date" class="form-control" name='meta[vote_by_date]' id="voteByDate" placeholder="Vote by date" value="{{ (!empty($metaData['vote_by_date'])) ? $metaData['vote_by_date'] : '' }}">
                </div>
                <div class="form-group-two col-4">
                    <label for="aIconFile" class="form-label">Affiliation Icon</label>
                    <x-display-image :data="$data?$data : ''" :fileName="'affiliation_icon'" ></x-display-image>
                </div>
                <div class="form-group-two col-4">
                    <label for="formFile" class="form-label">Politician Image</label>
                    <x-display-image :data="$data?$data : ''" :required="'true'"></x-display-image>
                </div>
            </div>

            <div class="form-group">

                <div id="repeater">
                    <!-- Repeater Heading -->
                    <div class="repeater-heading">
                        <label for="politicalPositions">Political Positions</label>
                        <span class="btn btn-primary pt-5 repeater-add-btn">Add</span>
                    </div>
                    <!-- Repeater Items -->

                    <!-- Repeater Content -->
                    
                    @if(!empty($meta_data) && isset($meta_data['p_pos']))
                        @foreach ($meta_data['p_pos'] as $item)
                            @include('politician.position-div')
                        @endforeach
                    @endif
                    <div class="items" data-group="p_pos">
                        <div class="item-content">
                            <div class="row">
                                <div class="col-3">
                                    <input type="text" class="form-control" id="inputName" placeholder="Name" data-name="name">
                                </div>
                                <div class="col-2">
                                    <select class="form-control" data-name="res">
                                        <option>--Choose--</option>
                                        <option>Supports</option>
                                        <option>Rejects</option>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <textarea class="form-control" id="inputPpDescription" placeholder="Description" data-name="ppdescription"></textarea>
                                </div>
                                <div class="pull-right repeater-remove-btn col-2">
                                    <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Repeater Remove Btn -->

                        <div class="clearfix"></div>
                    </div>
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