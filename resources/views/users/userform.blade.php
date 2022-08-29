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
                    <input type="text" required class="form-control" name='meta[p_first_name]' id="p_firstName" placeholder="First Name" value="{{ (!empty($metaData['p_first_name'])) ? $metaData['p_first_name'] : '' }}">
                </div>
                <div class="form-group col-3">
                    <input type="text" required class="form-control" name='meta[p_last_name]' id="p_lastName" placeholder="Last Name" value="{{ (!empty($metaData['p_last_name'])) ? $metaData['p_last_name'] : '' }}">
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

        <div class="row">
            <div class="col-6">
                <hr>
            </div>
        </div>


        <form class="needs-validation-2" action="{{route('post.warn')}}" method="post" id="validUserForm" name="form_02" novalidate>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row">
                <div class="col-6">
                    <h6 class="text-divider"><span>WARN USER</span></h6>
                </div>
            </div>

            <div class="row">
                <div class="form-group quill-field-block col-6">
                    <label for="inputDescription">Warn Message</label>
                    <textarea class="ckeditor" id="inputDescription" name='warn_message' placeholder="Enter description"></textarea>
                </div>
            </div>

            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

            <div class="row">
                <div class="col-6">
                    <input type="submit" name='Send' class="btn btn-warning float-right"  value="Send">
                </div>
            </div>

        </form>

        <div class="row mb-4">
            <div class="col-6">
                <p class="text-divider"><span>Warn Log</span></p>
                <div class="card">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Warn Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($i = 1)
                            @foreach ($user_warns as $user_warn)
                            <tr>
                                <th scope="row">{{ $i }}</th>
                                <td>{{ date_format($user_warn->created_at,"Y/m/d") }}</td>
                                <td>{!! $user_warn->warn_message !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <form class="needs-validation-1" action="{{route('post.ban')}}" method="post" id="userbanform" name="form_03" enctype="multipart/form-data" novalidate>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            {{-- User Ban --}}

            <div class="row">
                <div class="col-6">
                    <h6 class="text-divider"><span>BAN user account</span></h6>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-6">
                    <label for="ban_till">Ban Duration</label>
                    <input type="date" required class="form-control" name='ban_till' id="ban_till" placeholder="Ban Duration" value="{{ (!empty($data)) ? $data->ban_till : '' }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label for="ban_reason">Ban Reason</label>
                    <input type="text" required class="form-control" name='ban_reason' id="ban_reason" placeholder="Ban Reason" value="{{ (!empty($data)) ? $data->ban_reason : '' }}">
                </div>
            </div>


            <div class="row">
                <div class="form-group col-6">

                    {{-- <div class="display_status">Ban Status</div> --}}
                    <div class="form-group">
                        <input type="checkbox" {{((!empty($data) && $data->user_ban == 1)) ? 'checked' : ''}} data-toggle="toggle" name="user_ban" value="1" data-on="Activate" data-off="Deactivate" data-onstyle="primary">
                    </div>
                </div>
            </div>

            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

            <div class="row">
                <div class="col-6">
                    <input type="submit" name='Send' class="btn btn-primary float-right"  value="Save">
                </div>
            </div>

        </form>

        <div class="row mb-4">
            <div class="col-6">
                <p class="text-divider"><span>User Ban Log</span></p>
                <div class="card">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Ban Duration</th>
                                <th scope="col">Ban Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($i = 1)
                            @foreach ($user_bans as $user_ban)
                            <tr>
                                <th scope="row">{{ $i }}</th>
                                <td>{{ $user_ban->ban_till }}</td>
                                <td>{!! $user_ban->ban_reason !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <form class="needs-validation-1" action="{{route('post.block')}}" method="post" id="validUserForm" name="form_01" enctype="multipart/form-data" novalidate>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            {{-- User Block --}}

            <div class="row">
                <div class="col-6">
                    <h6 class="text-divider"><span>Block user account</span></h6>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label for="block_reason">Block Reason</label>
                    <input type="text" required class="form-control" name='block_reason' id="block_reason" placeholder="Block Reason" value="{{ (!empty($data)) ? $data->block_reason : '' }}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <div class="form-group">
                        <input type="checkbox" {{((!empty($data) && $data->user_block == 1)) ? 'checked' : ''}} data-toggle="toggle" name="user_block" value="1" data-on="Activate" data-off="Deactivate" data-onstyle="primary">
                    </div>
                </div>
            </div>

            <input type="hidden" name="id" value="{{$data?$data->id : ''}}">

            <div class="row">
                <div class="col-6">
                    <input type="submit" name='Send' class="btn btn-primary float-right"  value="Save">
                </div>
            </div>

        </form>

        <div class="row mb-4">
            <div class="col-6">
                <p class="text-divider"><span>User Block Log</span></p>
                <div class="card">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Block Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($i = 1)
                            @foreach ($user_blocks as $user_block)
                            <tr>
                                <th scope="row">{{ $i }}</th>
                                <td>{{ date_format($user_block->created_at,"Y/m/d") }}</td>
                                <td>{!! $user_block->block_reason !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
    @push('scripts')

        <script>
            $('document').ready(function(){
                $.validator.addMethod("pwcheck", function(value) {
                    return /^[A-Za-z0-9\d=!\-@._`!@#$%^&*()_+\-=\\[\]{};':"\\|,.<>\\/?~*]*$/.test(value) // consists of only these
                        && /[a-z]/.test(value) // has a lowercase letter
                        && /[A-Z]/.test(value) // has a lowercase letter
                        && /[ `!@#$%^&*()_+\-=\\[\]{};':"\\|,.<>\\/?~]/.test(value) // has a special
                        && /\d/.test(value) // has a digit
                });
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
        <script>
            $('.ckeditor').ckeditor();
        </script>
    @endpush
</x-app-layout>
