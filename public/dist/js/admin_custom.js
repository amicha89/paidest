'use strict';

// Dispute Section
if ($('.content-wrapper').find('#discussion-reply').length) {
    $('#reply').validate({
		rules: {
			description: {
				required: true,
			},
			file: {
                extension: extensionsValidationRule,
            },
		},
		messages: {
			file: {
				extension: extensionsValidationMessage
			},
        },
        submitHandler: function(form)
        {
            $("#dispute-reply").attr("disabled", true).click(function (e)
	        {
	            e.preventDefault();
	        });
            $(".fa-spin").show();
            $("#dispute-reply-text").text(submittingText);
            form.submit();
        }
	});

	$("#status").on('change', function()
	{
		var status = $(this).val();
		var id = $("#id").val();

		$.ajax({
			method: "POST",
			url: ADMIN_URL + "/dispute/change_reply_status",
			data: { status: status, id:id}
		})
	    .done(function(data)
	    {
	    	message = 'Dispute discussion '+ status +' successfully done.';
	    	message = statusChangeText.replace(":x", status);
	    	var messageBox = '<div class="alert alert-success" role="alert">'+ message +'</div><br>';
	    	$("#alertDiv").html(messageBox);
			location.reload().delay(10000);
	    });
	});
}
// Ticket Section
if ($('.content-wrapper').find('#ticket-reply').length) {
    $(function () {
        $(".select2").select2({
        });
    });

    $(function () {
        $('.message').wysihtml5({
            events: {
                change: function () {
                    if($('.message').val().length === 0 ) {
                        $('#error-message').addClass('error').html('This field is required.').css("font-weight", "bold");
                    } else {
                        $('#error-message').html('');
                    }
                }
            }
        });
    });

    $(function () {
        $('.editor').wysihtml5({
            events: {
                change: function () {
                    if($('.editor').val().length === 0 ) {
                        $('#error-message-modal').addClass('error').html('This field is required.').css("font-weight", "bold");
                    } else {
                        $('#error-message-modal').html('');
                    }
                }
            }
        });
    });

    $.validator.setDefaults({
        highlight: function(element) {
            $(element).parent('div').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).parent('div').removeClass('has-error');
        },
        errorPlacement: function (error, element)
        {
            if (element.prop('name') === 'message') {
                $('#error-message').html(error);
            } else if (element.prop('id') === 'editor') {
                $('#error-message-modal').html(error);
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#reply_form').validate({
        ignore: ":hidden:not(textarea)",
        rules: {
            message: "required",
            file: {
                extension: extensions,
            },
        },
        messages: {
            file: {
                extension: fileErrorMessage
            },
        },
        submitHandler: function(form)
        {
            $("#reply").attr("disabled", true);
            $(".fa-spin").show();
            $("#reply_text").text('Replying...');

            $("#customer_reply_button").attr("disabled", true);
            $("#admin_reply_button").attr("disabled", true);
            $(".edit-btn").attr("disabled", true);

            $('#customer_reply_button').click(false);
            $('#admin_reply_button').click(false);
            $('.edit-btn').click(false);
            form.submit();
        }
    });

    $('#replyModal').validate({
        rules: {
            message:{
               required: true,
            }
        }
    });


    $( document ).ready(function(e)
    {
        $(".edit-btn").on('click', function()
        {
            var id = $(this).attr('data-id');
            var message = $(this).attr('data-message');
            if (message) {
                $('#replyModal iframe').contents().find('.wysihtml5-editor').html(message);
            }
            $("#reply_id").val(id);
        });
    });

    $(document).on('input','#status_ticket',function()
    {
        var status_id = $("#status_ticket").val();

        $.ajax({
            url: ticketStatusChangeUrl,
            method: "POST",
            data:{
                'status_id': status_id,
                'ticket_id': ticket_id,
                '_token': token
            },
            dataType:"json",
            success:function(data)
            {
                if(data.status == '1' ) {
                    $('#status_label').html(data.message);
                }
            }
        });
    });
}

// User Section
// user create
if ($('.content-wrapper').find('#user-create').length) {
    $(function () {
        $(".select2").select2({
        });
    });

    function enableDisableButton()
    {
        if (!hasPhoneError && !hasEmailError) {
            $('form').find("button[type='submit']").prop('disabled',false);
        } else {
            $('form').find("button[type='submit']").prop('disabled',true);
        }
    }

    function formattedPhone()
    {
        if ($('#phone').val != '') {
            var p = $('#phone').intlTelInput("getNumber").replace(/-|\s/g,"");
            $("#formattedPhone").val(p);
        }
    }

    

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


    $('#user_form').validate({
        rules: {
            first_name: {
                required: true,
                alpha: true,
                maxlength: 30,
            },
            last_name: {
                required: true,
                alpha: true,
                maxlength: 30,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 6,
            },
            password_confirmation: {
                required: true,
                minlength: 6,
                equalTo: "#password",
            },
        },
        messages: {
            password_confirmation: {
                equalTo: passwordMatchErrorText
            },
            first_name: {
                alpha: userNameError,
                maxlength: userNameLengthError
            },
            last_name: {
                alpha: userNameError,
                maxlength: userNameLengthError
            },
        },
        submitHandler: function(form)
        {
            $("#users_create").attr("disabled", true);
            $(".fa-spin").show();
            $("#users_create_text").text(creatingText);
            $('#users_cancel').attr("disabled",true);
            form.submit();
        }
    });

    //user name validation
    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    });

    $(document).ready(function()
    {
        $("#phone").intlTelInput({
            separateDialCode: true,
            nationalMode: true,
            preferredCountries: [countryShortCode],
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            utilsScript: utilsScriptLoadingPath
        });

        var countryData = $("#phone").intlTelInput("getSelectedCountryData");
        $('#defaultCountry').val(countryData.iso2);
        $('#carrierCode').val(countryData.dialCode);

        $("#phone").on("countrychange", function(e, countryData)
        {
            formattedPhone();
            $('#defaultCountry').val(countryData.iso2);
            $('#carrierCode').val(countryData.dialCode);

            if ($.trim($(this).val()) !== '') {
                if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
                    $('#tel-error').addClass('error').html(validPhoneNumberErrorText).css("font-weight", "bold");
                    hasPhoneError = true;
                    enableDisableButton();
                    $('#phone-error').hide();
                } else {
                    $('#tel-error').html('');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "POST",
                        url: ADMIN_URL + "/duplicate-phone-number-check",
                        dataType: "json",
                        cache: false,
                        data: {
                            'phone': $.trim($(this).val()),
                            'carrierCode': $.trim(countryData.dialCode),
                        }
                    })
                    .done(function(response)
                    {
                        if (response.status == true) {
                            $('#tel-error').html('');
                            $('#phone-error').show();

                            $('#phone-error').addClass('error').html(response.fail).css("font-weight", "bold");
                            hasPhoneError = true;
                            enableDisableButton();
                        } else if (response.status == false) {
                            $('#tel-error').show();
                            $('#phone-error').html('');

                            hasPhoneError = false;
                            enableDisableButton();
                        }
                    });
                }
            } else {
                $('#tel-error').html('');
                $('#phone-error').html('');
                hasPhoneError = false;
                enableDisableButton();
            }
        });
    });

    //Invalid Number Validation - admin create
    $(document).ready(function() {
        $("input[name=phone]").on('blur', function(e) {
            formattedPhone();
            if ($.trim($(this).val()) !== '') {
                if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
                    $('#tel-error').addClass('error').html(validPhoneNumberErrorText).css("font-weight", "bold");
                    hasPhoneError = true;
                    enableDisableButton();
                    $('#phone-error').hide();
                } else {
                    var phone = $(this).val().replace(/-|\s/g,""); //replaces 'whitespaces', 'hyphens'
                    var phone = $(this).val().replace(/^0+/,"");  //replaces (leading zero - for BD phone number)

                    var pluginCarrierCode = $('#phone').intlTelInput('getSelectedCountryData').dialCode;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "POST",
                        url: ADMIN_URL + "/duplicate-phone-number-check",
                        dataType: "json",
                        data: {
                            'phone': phone,
                            'carrierCode': pluginCarrierCode,
                        }
                    })
                    .done(function(response)
                    {
                        if (response.status == true) {
                            if(phone.length == 0) {
                                $('#phone-error').html('');
                            } else{
                                $('#phone-error').addClass('error').html(response.fail).css("font-weight", "bold");
                                hasPhoneError = true;
                                enableDisableButton();
                            }
                        } else if (response.status == false) {
                            $('#phone-error').html('');
                            hasPhoneError = false;
                            enableDisableButton();
                        }
                    });
                    $('#tel-error').html('');
                    $('#phone-error').show();
                    hasPhoneError = false;
                    enableDisableButton();
                }
            } else {
                $('#tel-error').html('');
                $('#phone-error').html('');
                hasPhoneError = false;
                enableDisableButton();
            }
        });
    });

    $(document).ready(function()
    {
        $("#email").on('blur', function(e) {
            var email = $('#email').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: ADMIN_URL + "/email_check",
                dataType: "json",
                data: {
                    'email': email,
                }
            })
            .done(function(response)
            {
                if (response.status == true) {
                    emptyEmail();
                    if (validateEmail(email)) {
                        $('#email_error').addClass('error').html(response.fail).css("font-weight", "bold");
                        $('#email_ok').html('');
                        hasEmailError = true;
                        enableDisableButton();
                    } else {
                        $('#email_error').html('');
                    }
                } else if (response.status == false) {
                    emptyEmail();
                    if (validateEmail(email)) {
                        $('#email_error').html('');
                    } else {
                        $('#email_ok').html('');
                    }
                    hasEmailError = false;
                    enableDisableButton();
                }

                /**
                 * [validateEmail description]
                 * @param  {null} email [regular expression for email pattern]
                 * @return {null}
                 */
                function validateEmail(email) {
                    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(email);
                }

                /**
                 * [checks whether email value is empty or not]
                 * @return {void}
                 */
                function emptyEmail() {
                    if( email.length === 0 ) {
                        $('#email_error').html('');
                        $('#email_ok').html('');
                    }
                }
            });
        });
    });
}
// user edit
if ($('.content-wrapper').find('#user-edit').length) {
    $(function () {
        $(".select2").select2({
        });

        $("#phone").intlTelInput({
            separateDialCode: true,
            nationalMode: true,
            preferredCountries: ["us"],
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            formatOnDisplay: false,
            utilsScript: utilsScriptLoadingPath
        })
        .done(function()
        {
            if (formattedPhoneNumber !== null && carrierCode !== null && defaultCountry !== null) {
                $("#phone").intlTelInput("setNumber", formattedPhoneNumber);
                $('#user_defaultCountry').val(defaultCountry);
                $('#user_carrierCode').val(carrierCode);
            }
        });
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

    function formattedPhone()
    {
        if ($('#phone').val != '') {
            let p = $('#phone').intlTelInput("getNumber").replace(/-|\s/g, "");
            $("#formattedPhone").val(p);
        }
    }

    function checkInvalidAndDuplicatePhoneNumberForUserProfile(phoneVal, phoneData, userId)
    {
        var that = $("input[name=phone]");
        if ($.trim(that.val()) !== '') {
            if (!that.intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim(that.val()))) {
                $('#tel-error').addClass('error').html(validPhoneNumberErrorText).css("font-weight", "bold");
                hasPhoneError = true;
                enableDisableButton();
                $('#phone-error').hide();
            } else {
                $('#tel-error').html('');

                var id = $('#id').val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: ADMIN_URL + "/duplicate-phone-number-check",
                    dataType: "json",
                    cache: false,
                    data: {
                        'phone': phoneVal,
                        'carrierCode': phoneData,
                        'id': userId,
                    }
                })
                .done(function(response)
                {
                    if (response.status == true) {
                        $('#tel-error').html('');
                        $('#phone-error').show();

                        $('#phone-error').addClass('error').html(response.fail).css("font-weight", "bold");
                        hasPhoneError = true;
                        enableDisableButton();
                    } else if (response.status == false) {
                        $('#tel-error').show();
                        $('#phone-error').html('');

                        hasPhoneError = false;
                        enableDisableButton();
                    }
                });
            }
        } else {
            $('#tel-error').html('');
            $('#phone-error').html('');
            hasPhoneError = false;
            enableDisableButton();
        }
    }

    var countryData = $("#phone").intlTelInput("getSelectedCountryData");
    $('#user_defaultCountry').val(countryData.iso2);
    $('#user_carrierCode').val(countryData.dialCode);

    $("#phone").on("countrychange", function(e, countryData)
    {
        $('#user_defaultCountry').val(countryData.iso2);
        $('#user_carrierCode').val(countryData.dialCode);
        formattedPhone();
        var id = $('#id').val();
        //Invalid Phone Number Validation
        checkInvalidAndDuplicatePhoneNumberForUserProfile($.trim($(this).val()), $.trim(countryData.dialCode), id);
    });

    //Duplicated Phone Number Validation
    $("#phone").on('blur', function(e)
    {
        formattedPhone();
        var id = $('#id').val();
        var phone = $(this).val().replace(/-|\s/g,""); //replaces 'whitespaces', 'hyphens'
        var phone = $(this).val().replace(/^0+/,"");  //replaces (leading zero - for BD phone number)
        var pluginCarrierCode = $(this).intlTelInput('getSelectedCountryData').dialCode;
        checkInvalidAndDuplicatePhoneNumberForUserProfile(phone, pluginCarrierCode, id);
    });

    // Validate email via Ajax
    $(document).ready(function()
    {
        $("#email").on('input', function(e) {
            var email = $(this).val();
            var id = $('#id').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: ADMIN_URL + "/email_check",
                dataType: "json",
                data: {
                    'email': email,
                    'user_id': id,
                }
            })
            .done(function(response)
            {
                emptyEmail(email);
                if (response.status == true) {

                    if (validateEmail(email)) {
                        $('#emailError').addClass('error').html(response.fail).css("font-weight", "bold");
                        $('#email-ok').html('');
                        hasEmailError = true;
                        enableDisableButton();
                    } else {
                        $('#emailError').html('');
                    }
                }
                else if (response.status == false) {
                    hasEmailError = false;
                    enableDisableButton();
                    if (validateEmail(email)) {
                        $('#emailError').html('');
                    } else {
                        $('#email-ok').html('');
                    }
                }

                /**
                 * [validateEmail description]
                 * @param  {null} email [regular expression for email pattern]
                 * @return {null}
                 */
                function validateEmail(email) {
                    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(email);
                }

                /**
                 * [checks whether email value is empty or not]
                 * @return {void}
                 */
                function emptyEmail(email) {
                    if( email.length === 0 ) {
                        $('#emailError').html('');
                        $('#email-ok').html('');
                    }
                }
            });
        });
    });

    // show warnings on user status change
    $(document).on('change', '#status', function() {
        let status = $('#status').val();
        if (status == 'Inactive') {
            $('#user-status').text(inactiveWarning);
        } else if (status == 'Suspended') {
            $('#user-status').text(suspendWarning);
        } else {
            $('#user-status').text('');
        }
    });

    $('#user_form').validate({
        rules: {
            first_name: {
                required: true,
                alpha: true,
                maxlength: 30,
            },
            last_name: {
                required: true,
                alpha: true,
                maxlength: 30, 
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                minlength: 6,
            },
            password_confirmation: {
                minlength: 6,
                equalTo: "#password",
            },
        },
        messages: {
            password_confirmation: {
              equalTo: passwordMatchErrorText
            },
            first_name: {
                alpha: userNameError,
                maxlength: userNameLengthError
            },
            last_name: {
                alpha: userNameError,
                maxlength: userNameLengthError
            },
            
        },
        submitHandler: function(form)
        {
            $("#users_edit").attr("disabled", true);
            $(".fa-spin").show();
            $("#users_edit_text").text(updatingText);
            $('#users_cancel').attr("disabled","disabled");
            form.submit();
        }
    });

    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    });
}