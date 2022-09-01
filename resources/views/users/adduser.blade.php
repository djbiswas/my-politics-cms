<x-app-layout>
    <x-slot name="header">
        <h4>{{$data?'Edit' : 'Add'}}  User</h4>
    </x-slot>
    <div class="generic-form" style="text-align: left;">
        <form class="needs-validation-1" action="{{route('post.user')}}" method="post" id="validUserForm" name="form_01" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

            <div class="row a-i-center">
                <div class="form-group col-3">
                    <label for="email">Email</label>
                    <input type="email"  class="form-control" name='email' id="email" placeholder="Email" value="{{ (!empty($data)) ? $data->email : '' }}">
                </div>

                <div class="form-group col-3 user-phone">
                    <label for="phone">Phone</label>
                    <input type="text" <?php echo isset($_GET['id'])?'readonly':'required'; ?> class="form-control" name='phone' id="phone" placeholder="Phone" value="{{ (!empty($data)) ? $data->phone : '' }}">
                </div>
            </div>

            <div class="row a-i-end">
                <div class="form-group col-3">
                    <label for="penName">Pen Name</label>
                    <input type="text" class="form-control" name='meta[p_first_name]' id="p_firstName" placeholder="First Name" value="{{ (!empty($metaData['p_first_name'])) ? $metaData['p_first_name'] : '' }}">
                </div>
                <div class="form-group col-3">
                    <input type="text" class="form-control" name='meta[p_last_name]' id="p_lastName" placeholder="Last Name" value="{{ (!empty($metaData['p_last_name'])) ? $metaData['p_last_name'] : '' }}">
                </div>
            </div>

            <div class="row a-i-end">
                <div class="form-group col-3">
                    <label for="userName">User Name</label>
                    <input type="text" required class="form-control" name='first_name' id="firstName" placeholder="First Name" value="{{ (!empty($data)) ? $data->first_name : '' }}">
                </div>
                <div class="form-group col-3">
                    <input type="text" required class="form-control" name='last_name' id="lastName" placeholder="Last Name" value="{{ (!empty($data)) ? $data->last_name : '' }}">
                </div>
            </div>

            {{-- <div class="row {{ (!empty($data)) ? 'hide' : '' }} "> --}}

            <div class="row ">
                <div class="form-group col-3">
                    <label for="password">Password </label>
                    <input type="password" class="form-control" name='password' id="password" placeholder="Password" value="" >
                </div>

                <div class="form-group col-3">
                    <label for="re_password">Confirm Password </label>
                    <input type="password" class="form-control" name='re_password' id="re_password" placeholder="Confirm Password" value="" >
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label for="address">Address</label>
                    <input type="text" required class="form-control" name='meta[address]' id="address" placeholder="Address" value="{{ (!empty($metaData['address'])) ? $metaData['address'] : '' }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label for="zipcode">Zipcode</label>
                    <input type="text" required class="form-control" name='meta[zipcode]' id="zipcode" placeholder="Zipcode" value="{{ (!empty($metaData['zipcode'])) ? $metaData['zipcode'] : '' }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-3">
                    <label for="zipcode">Rank</label>
                    <select required class="form-control" name='rank_id' id="selectRank">
                        <option value="">--Select--</option>
                        @if(!empty($ranks))
                            @foreach ($ranks as $item)
                                <?php
                                    $rank_sel = (!empty($data) && $data->rank_id == $item['id']) ? 'selected' : '';
                                ?>
                                <option {{$rank_sel}} value="{{$item['id']}}">{{$item['title']}}</option>;
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-3">
                    {!! Form::label('role_id', 'Select User Role',) !!}
                    {!! Form::select('role_id', $roles, $data? $data->role_id : null, ['class' => 'form-control', 'placeholder' => '--Select--', 'requird']) !!}
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group-two col-6">
                    <label for="formFile" class="form-label">User Image</label>
                    <x-display-image :data="$data?$data : ''" :required="'true'"></x-display-image>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label for="lockRank">Lock Rank</label>
                    <input type="checkbox" name='lock_rank' id="lockRank" value="1" {{ (!empty($data->lock_rank) && $data->lock_rank == 1) ? 'checked' : '' }}>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-6">
                    <div class="display_status">User Status</div>
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

            <div class="row">
                <div class="col-6">
                    <input type="submit" name='Save' class="btn btn-primary float-right"  value="Save">
                </div>
            </div>

        </form>

    </div>
    @push('scripts')
    {{-- <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script> --}}
        <script>
            $('document').ready(function(){
                $.validator.addMethod("pwcheck", function(value) {
                    return /^[A-Za-z0-9\d=!\-@._`!@#$%^&*()_+\-=\\[\]{};':"\\|,.<>\\/?~*]*$/.test(value) // consists of only these
                        && /[a-z]/.test(value) // has a lowercase letter
                        && /[A-Z]/.test(value) // has a lowercase letter
                        && /[ `!@#$%^&*()_+\-=\\[\]{};':"\\|,.<>\\/?~]/.test(value) // has a special
                        && /\d/.test(value) // has a digit
                });

                $.validator.addMethod("ziprange", function(value, element) {
                    return this.optional(element) || /^90[2-5]\d\{2\}-\d{4}$/.test(value);
                }, "Your ZIP-code must be in the range 902xx-xxxx to 905-xx-xxxx");
                // }, "Your ZIP-code must be in the range 902xx-xxxx to 905-xx-xxxx");


                $.validator.addMethod("zipcodeUS", function(value, element) {
                    return this.optional(element) || /\d{5}-\d{4}$|^\d{5}$/.test(value);
                }, "The specified US ZIP Code is invalid");

                $.validator.addMethod("notEqualTo", function(value, element, param) {
                    return this.optional(element) || value != param;
                }, "The specified US ZIP Code is invalid");


                $("#validUserForm").validate({
                    ignore:":not(:visible)",
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('has-error was-validated');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error was-validated');
                    },
                    errorClass:"error error_preview",
                    rules: {
                        first_name:{
                            required:true
                        },
                        password:{
                            required:true,
                            pwcheck : true,
                            minlength:10
                        },
                        re_password:{
                            required:true,
                            equalTo : "#password"

                        },
                        "meta[zipcode]":{
                            required: true,
                            zipcodeUS: true,
                            notEqualTo: "00000"
                        },
                        email:{
                            required:"#phone:blank",
                            email:true,
                            remote: {
                                url: "{{ route('check.user.email') }}",
                                type: "post",
                                beforeSend: function (xhr) { // Handle csrf errors
                                    xhr.setRequestHeader('X-CSRF-Token', "{{ csrf_token() }}");
                                    xhr.setRequestHeader('user-id', "{{ (!empty($data)) ? $data->id : '' }}");
                                },
                            }
                        },
                        phone:{
                            required:"#email:blank",
                            remote: {
                                url: "{{ route('check.user.phone') }}",
                                type: "post",
                                beforeSend: function (xhr) { // Handle csrf errors
                                    xhr.setRequestHeader('X-CSRF-Token', "{{ csrf_token() }}");
                                    xhr.setRequestHeader('user-id', "{{ (!empty($data)) ? $data->id : '' }}");
                                },
                            }
                        }

                    },
                    messages: {
                            password: {
                            pwcheck: "The password must have at least 10 characters including a number, a capital letter, a small letter and a special character."
                        },
                        re_password: {
                            pwcheck: "Password and Confirm Password does not match!"
                        },
                        email: {
                            remote: "Email already exist"
                        },
                        phone: {
                            remote: "Phone already exist"
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

        {{-- <script>
            $('.ckeditor').ckeditor();
        </script> --}}
        <script>
            $(function() {
              $('input[name="ban_till"]').daterangepicker({
                opens: 'left'
              }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
              });
            });
            </script>
    @endpush
</x-app-layout>
