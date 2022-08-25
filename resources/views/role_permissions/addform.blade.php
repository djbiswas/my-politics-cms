<div class="generic-form">
    <?php
        $edit_data = $sub_action = $sub_btn_text = "";
    ?>
    <h4>Assign role permissions</h4>

        {!! Form::open(['route' => 'post.role.permission', 'class' =>'needs-validation-1', 'id' => 'validRankForm','enctype' =>'multipart/form-data', 'novalidate'])  !!}


        {{-- <input type="hidden" name="id" value="{{$data?$data->id : ''}}"> --}}

        <div class="row">
            <div class="form-group col-12">
                {!! Form::label('role_id', 'Select Role',) !!}
                @if (isset($role_id))
                    {!! Form::select('role_id', $roles, $data? $role_id : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird', 'readonly', 'disabled']) !!}
                @else
                    {!! Form::select('role_id', $roles, $data? $role_id : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird']) !!}
                @endif
            </div>
        </div>

        <span>Select Permissions For the Selected Role</span>

        <hr>

        <div class="row">
            @php $checkbox_auto_id = 1; @endphp
            @foreach ($permissions as $permission_cat)

                <div class="form-group col-6">
                   <span> {{$permission_cat->name}}  <br>
                        ----------------
                    </span>
                    @foreach ( $permission_cat->permission as $permission)
                        @if (isset($permission_ids))
                            @if (in_array($permission->id, $permission_ids) )
                                <div class="form-check">
                                    <input type="checkbox" name="permission_id[]" class="form-check-input checkfix" id="permission_id_{{$checkbox_auto_id}}" value="{{$permission->id}}" checked>
                                    <label class="form-check-label" for="permission_id_{{$checkbox_auto_id}}" >{{$permission->name}}</label>
                                </div>
                            @else
                                <div class="form-check">
                                    <input type="checkbox" name="permission_id[]" class="form-check-input checkfix" id="permission_id_{{$checkbox_auto_id}}" value="{{$permission->id}}">
                                    <label class="form-check-label" for="permission_id_{{$checkbox_auto_id}}" >{{$permission->name}}</label>
                                </div>
                            @endif
                        @else
                            <div class="form-check">
                                <input type="checkbox" name="permission_id[]" class="form-check-input checkfix" id="permission_id_{{$checkbox_auto_id}}" value="{{$permission->id}}">
                                <label class="form-check-label" for="permission_id_{{$checkbox_auto_id}}" >{{$permission->name}}</label>
                            </div>
                        @endif


                        @php $checkbox_auto_id = $checkbox_auto_id + 1; @endphp
                    @endforeach
                </div>
            @endforeach
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
                                xhr.setRequestHeader('permission-id', "{{ (!empty($data)) ? $role_id : '' }}");
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
