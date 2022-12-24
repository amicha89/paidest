@extends('frontend.layouts.app')
@section('content')
<!--Start banner Section-->
<style>
    #password {
  padding: 14px 10px;
}
</style>
<section class="bg-image mt-93">
    <div class="bg-dark">
        <div class="container">
            <div class="row py-5">
                <div class="col-md-12">
                    <h2 class="text-white font-weight-bold text-28">Create Your Password</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!--End banner Section-->

<!--Start Section-->
<section class="sign-up padding-30 pb-44">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-8 mt-5">
                        <!-- form card login -->
                        <div class="card p-4 rounded-0">
                            <!-- <div>
                                <h3 class="mb-0 font-weight-bold">Verify Your Email Address</h3>
                            </div> -->
                            @include('frontend.layouts.common.alert')
                            <div class="alert alert-info">
                                <h4 class="alert-heading"><i class="fas fa-exclamation-circle"></i>&nbsp;Password Must be...</h4>
                            <div class="pt-2 pl-4">
                                <ul style="list-style-type:disc">
                                    <li>Between 8 and 30 characters.</li>
                                    <li>Include a lowercase character.</li>
                                    <li>Include an uppercase character.</li>
                                    <li>Include a digit and a special character.</li>
                                    <li>Different from any of the 5 last such passwords used.</li>
                                </ul>
                            </div>
<!-- action="{{ route('password-store') }}" -->
                            </div>
							<div class="card-body">

								<form  id="create-password">
										{{ csrf_field() }}                                      
                                        <div class="form-group">
											<label for="password_only">Email</label>
											<input type="input" value="{{ $userEmail ?? '' }}" class="form-control"  placeholder="" readonly onclick='return false;' name="u" id="email">

											@if($errors->has('password'))
												<span class="error">
													{{ $errors->first('password') }}
												</span>
											@endif
										</div>

										<div class="form-group">
											<label for="password_only">Password</label>
                                            <div id="password" class="form-control"></div><br/>
											

											@if($errors->has('password'))
												<span class="error">
													{{ $errors->first('password') }}
												</span>
											@endif
										</div>
									
				
									<div class="row">
										<div class="col-md-8">
										<button type="button" onclick="onSubmit()" class="btn btn-grad mt-4" id="password_create">
											<i class="spinner fa fa-spinner fa-spin" style="display: none;"></i> 
											<span id="password_create">Submit</span>
										</button>
										</div>
                                        <div class="col-md-4 get-color text-center" style="margin: 32px 0 6px 0px;" ><a href="{{url('forget-password')}}">@lang('message.login.forget-password')</a></div>
									</div>
                                    <!-- <div class="row">
                                        <div class="col-md-12 get-color" style="margin: -2px 0 6px 0px;">
                                            <br>
                                            <a href="{{url('forget-password')}}">@lang('message.login.forget-password')</a>
                                        </div>
                                    </div> -->

								</form>
							</div>
							<!--/card-block-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="https://build.weavr.io/app/secure/static/client.1.js"></script>
<script src="{{theme_asset('public/js/jquery.validate.min.js')}}" type="text/javascript"></script>

<script type="text/javascript">
    // weavr password has creating request
    // Initialise the UI library using your ui_key. Replace ui_key with your own UI key.
    window.OpcUxSecureClient.init('{{$ui_key}}');

    // Create an instance of a secure form
    var form = window.OpcUxSecureClient.form();
    
    // Create an instance of a secure Password component that will collect the password
    var input = form.input(
        'p', 
        'password', 
        {
        placeholder: 'Password', 
        maxlength: 20
        }
    );
    // Embed the secure Password component in the HTML element where you want
    // the Password component to the shown on screen
    input.mount(document.getElementById('password'));

    // Define this input so that the 'enter key' would submit the password input
    input.on('submit', () => {
    console.log('submit password input')
    })

    // Define a function that will be executed when the form is submitted by the user
    function onSubmit() {
        // Get the tokenised version of the password inputted by your customer
        form.tokenize(function(tokens) {
        
            //console.log(tokens.p);
            var uname = $('#email').val();
            var Formdata = {password:tokens.p, username:uname};
            console.log(Formdata);
            jQuery.ajax({
                url: '{{ route("password-store") }}',
                type: 'POST',
                data: Formdata,
                success: function (res) {
                    console.log(res.status);
                    window.location.href = '/wppaidest/login';
                },
                error: function (error) {
                    console.log(error);
                    location.reload();  
                }
            });
            
        });
    }
</script>
<!-- <script>
    // flag for button disable/enable
    var hasPhoneError = false;
    var hasEmailError = false;

        //jquery validation
    $.validator.setDefaults({
    highlight: function(element) {
        $(element).parent('div').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).parent('div').removeClass('has-error');
    },
    errorPlacement: function (error, element) {
            error.insertAfter(element);
        }
    });

    jQuery.extend(jQuery.validator.messages, {
        required: "{{ __('This field is required.') }}",
        equalTo: "{{ __("Please enter the same value again.") }}",
        minlength: $.validator.format( "{{ __("Please enter at least") }}"+" {0} "+"{{ __("characters.") }}" ),
        password_confirmation: {
            equalTo: "{{ __("Please enter same value as the password field!") }}",
        },
    })

    $('#create_password').validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            },
            type: {
                required: true,
            },
        },
        messages: {
            password_confirmation: {
                equalTo: "{{ __("Please enter same value as the password field!") }}",
            }
        },
        submitHandler: function(form)
        {
            $("#users_create").attr("disabled", true).click(function (e)
            {
                e.preventDefault();
            });
            $(".spinner").show();
            $("#users_create_text").text("{{ __('Creating Password...') }}");
            form.submit();
        }
    });

    /**
    * [check submit button should be disabled or not]
    * @return {void}
    */
    function enableDisableButton()
    {
        if (!hasPhoneError && !hasEmailError) {
            $('form').find("button[type='submit']").prop('disabled',false);
        } else {
            $('form').find("button[type='submit']").prop('disabled',true);
        }
    }

    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    });
</script> -->
@endsection
