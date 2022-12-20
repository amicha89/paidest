'use strict';

// Dispute
// Dispute Discussion
if ($('.main-content').find('#dispute-discussion').length) {
    const actualBtn = document.getElementById('file');
	const fileChosen = document.getElementById('file-chosen');

	jQuery.extend(jQuery.validator.messages, {
		required: requirdFieldText
	})

	$('#reply').validate({
		rules: {
				description: {
					required: true,
				},
				file: {
					extension: extensionsValidationRule
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
				$(".spinner").show();
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
			url: SITE_URL+"/dispute/change_reply_status",
			data: { status: status, id:id}
		})
		.done(function( data )
		{
			if (status == 'Open') { status = 'Open'}
			else if (status == 'Solve') { status = 'Solved'}
			else if (status == 'Close') { status = 'Closed'}

			message = statusChangeText.replace(':x', status);
			var messageBox = '<div class="alert alert-success" role="alert">'+ message +'</div><br>';
			$("#alertDiv").html(messageBox);

			setTimeout(function() {
				location.reload()
			}, 2000);
		});
	});
}

// Ticket Section
// Ticket Reply
if ($('.main-content').find('#ticket-reply').length) {
    const actualBtn = document.getElementById('file');
    const fileChosen = document.getElementById('file-chosen');

    actualBtn.addEventListener('change', function(){
        fileChosen.textContent = this.files[0].name
    })

    jQuery.extend(jQuery.validator.messages, {
        required: requirdFieldText
    })

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
            $("#ticket-reply").attr("disabled", true).click(function (e)
            {
                e.preventDefault();
            });
            $(".spinner").show();
            $("#ticket-reply-text").text(submittingText);
            form.submit();
        }
    });

    $("#status").on('change', function () {
        var status_id = $(this).val();
        var ticket_id = $("#ticket_id").val();

        $.ajax({
            method: "POST",
            url: SITE_URL + "/ticket/change_reply_status",
            data: {
                status_id: status_id, 
                ticket_id: ticket_id
            }
        })
        .done(function (reply) {
            message = statusChangeText.replace(':x', reply.status); 
            var messageBox = '<div class="alert alert-success" role="alert">' + message + '</div><br>';
            $("#alertDiv").html(messageBox);
            setTimeout(function () {
                location.reload()
            }, 2000);
        });
    });
}

// UserProfile Section
// profile

function changeProfile() {
    $('#file').click();
}

if ($('.main-content').find('#user-profile').length) {
    $(document).on('click','#print-qr-code-btn',function(e)
    {
        e.preventDefault();
        let printQrCodeUrl = SITE_URL+'/profile/qr-code-print/'+userId+'/user';
        $(this).attr('href', printQrCodeUrl);
        window.open($(this).attr('href'), '_blank');
    });

    //show user's qr-code on window load
    $(window).on('load', function()
    {
        swal(pleaseWaitText, loadingText, {
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: false,
            timer: 2000,
        });

        if (QrCodeSecret != '') {
            $('.user-profile-qr-code').html(`<img src="https://api.qrserver.com/v1/create-qr-code/?data=${QrCodeSecret}&amp;size=200x200"/>`);
            $("#qr-code-btn").removeClass('add-qr-code').addClass('update-qr-code').text(updateQrCodeText);
            $("#print-qr-code-btn").show();
        } else {
            $(".user-profile-qr-code").html(`<img class="" src="${SITE_URL}/public/uploads/userPic/default-image.png" class="img-responsive"/>`);
            $("#qr-code-btn").addClass('add-qr-code').text(addQrCodeText);
        }
    });

    function addOrUpdateQrCode()
    {
        let user_id = $('#user_id').val();

        $.ajax({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: SITE_URL + "/profile/qr-code/add-or-update",
            dataType: "json",
            data: {
                'user_id': user_id,
            },
            beforeSend: function () {
                swal(pleaseWaitText, loadingText, {
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: false,
                    timer: 2000,
                });
            },
        })
        .done(function(response)
        {
            if (response.status == true) {
                $('.user-profile-qr-code').html(`<img src="https://api.qrserver.com/v1/create-qr-code/?data=${response.secret}&amp;size=200x200"/>`);
            }
        })
        .fail(function(error)
        {
            swal({
                title: errorText,
                text:  JSON.parse(error.responseText).exception,
                icon: "error",
                closeOnClickOutside: false,
                closeOnEsc: false,
            });
        });
    }

    // UPDATE USER's QR CODE
    $(document).on('click', '.update-qr-code', function(e)
    {
        e.preventDefault();
        addOrUpdateQrCode();
    });

    // ADD USER's QR CODE
    $(document).on('click', '.add-qr-code', function(e)
    {
        e.preventDefault();
        addOrUpdateQrCode();
        $("#qr-code-btn").removeClass('add-qr-code').addClass('update-qr-code').text(updateQrCodeText);
    });

 
    //reload on close of phone add modal
    $('#add').on('hidden.bs.modal', function ()
    {
        if ($("#phone").val() != '') {
            $(this).find("input").val('').end();
            $('#complete-phone-verification').validate().resetForm();
            window.location.reload();
        }
    });

    $(document).ready(function()
    {
        $("#phone").intlTelInput({
            separateDialCode: true,
            nationalMode: true,
            preferredCountries: [countryShortCode],
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            utilsScript: utilsScriptLoadingPath,
        });

        var countryData = $("#phone").intlTelInput("getSelectedCountryData");
        $('#defaultCountry').val(countryData.iso2);
        $('#carrierCode').val(countryData.dialCode);

        $("#phone").on("countrychange", function(e, countryData)
        {
            $('#defaultCountry').val(countryData.iso2);
            $('#carrierCode').val(countryData.dialCode);

            if ($.trim($(this).val())) {
                if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
                    $('#tel-number-error').addClass('error').html(validPhoneNumberErrorText).css({
                        'color' : '#f50000 !important',
                        'font-size' : '14px',
                        'font-weight' : '400',
                        'padding-top' : '5px',
                    });
                    $('#common_button').prop('disabled',true);
                    $('#phone-number-error').hide();
                } else {
                    $('#tel-number-error').html('');

                    var id = $('#id').val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "POST",
                        url: SITE_URL+"/profile/duplicate-phone-number-check",
                        dataType: "json",
                        cache: false,
                        data: {
                            'phone': $.trim($(this).val()),
                            'carrierCode': $.trim(countryData.dialCode),
                            'id': id,
                        }
                    })
                    .done(function(response)
                    {
                        if (response.status == true) {
                            $('#tel-number-error').html('');
                            $('#phone-number-error').show();

                            $('#phone-number-error').addClass('error').html(response.fail).css({
                                'color' : '#f50000 !important',
                                'font-size' : '14px',
                                'font-weight' : '400',
                                'padding-top' : '5px',
                            });
                            $('#common_button').prop('disabled',true);
                        } else if (response.status == false) {
                            $('#tel-number-error').show();
                            $('#phone-number-error').html('');

                            $('#common_button').prop('disabled',false);
                        }
                    });
                }
            } else {
                $('#tel-number-error').html('');
                $('#phone-number-error').html('');
                $('#common_button').prop('disabled',false);
            }
        });
    });
    
    //Invalid Number Validation - add
    $(document).ready(function()
    {
        $("#phone").on('blur', function(e)
        {
            if ($.trim($(this).val())) {
                if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
                    $('#tel-number-error').addClass('error').html(validPhoneNumberErrorText).css({
                        'color' : '#f50000 !important',
                        'font-size' : '14px',
                        'font-weight' : '400',
                        'padding-top' : '5px',
                    });
                    $('#common_button').prop('disabled',true);
                    $('#phone-number-error').hide();
                } else {
                    var id = $('#id').val();
                    var phone = $(this).val().replace(/-|\s/g,""); //replaces 'whitespaces', 'hyphens'
                    var phone = $(this).val().replace(/^0+/, ""); //replaces (leading zero - for BD phone number)

                    var pluginCarrierCode = $('#phone').intlTelInput('getSelectedCountryData').dialCode;

                    if (phone.length == 0) {
                        $('#phone-number-error').addClass('error').html(fieldRequiredText).css({
                            'color' : '#f50000 !important',
                            'font-size' : '14px',
                            'font-weight' : '400',
                            'padding-top' : '5px',
                        });
                        $('#common_button').prop('disabled',true);
                    } else {
                        $('#phone-number-error').hide();
                        $('#common_button').prop('disabled',false);
                    }

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "POST",
                        url: SITE_URL+"/profile/duplicate-phone-number-check",
                        dataType: "json",
                        cache: false,
                        data: {
                            'phone': phone,
                            'id': id,
                            'carrierCode': pluginCarrierCode,
                        }
                    })
                    .done(function(response)
                    {
                        $('#phone-number-error').show();
                        if (response.status == true) {
                            if (phone.length == 0) {
                                $('#phone-number-error').html('');
                            } else {
                                $('#phone-number-error').addClass('error').html(response.fail).css({
                                    'color' : '#f50000 !important',
                                    'font-size' : '14px',
                                    'font-weight' : '400',
                                    'padding-top' : '5px',
                                });
                                $('#common_button').prop('disabled',true);
                            }
                        } else if (response.status == false) {
                            $('#common_button').prop('disabled',false);
                            $('#phone-number-error').html('');
                        }
                    });
                    $('#tel-number-error').html('');
                    $('#phone-number-error').show();
                    $('#common_button').prop('disabled',false);
                }
            } else {
                $('#tel-number-error').html('');
                $('#phone-number-error').html('');
                $('#common_button').prop('disabled',false);
            }
        });
    });


    //is_sms_env_enabled and phone verification check
    $(document).ready(function()
    {
        var is_sms_env_enabled = $('#is_sms_env_enabled').val();
        var checkPhoneVerification = $('#checkPhoneVerification').val();

        if ((is_sms_env_enabled != true && checkPhoneVerification != "Enabled") || checkPhoneVerification != "Enabled") {
            $('.next').removeClass("next").addClass('form_submit').html(submitText);
        } else {
            $('.next').removeClass("form_submit").addClass('next').html(nextText);
        }
    });

    // next
    $(document).on('click', '.next', function()
    {
        var phone = $("input[name=phone]");
        if (phone.val() == '') {
            $('#phone-number-error').addClass('error').html(fieldRequiredText).css({
                'color' : '#f50000 !important',
                'font-size' : '14px',
                'font-weight' : '400',
                'padding-top' : '5px',
            });
            return false;
        } else if(phone.hasClass('error')) {
            return false;
        }
        else
        {
            $('.modal-title').html(getCodeText);
            $('#subheader_text').html(verificationCodeText);
            $('.phone_group').hide();
            $('#static_phone_show').show();
            $('.edit').show();

            $(this).removeClass("next").addClass("get_code").html(getCodeText);
            var fullPhone = $("#phone").intlTelInput("getNumber");
            $('#static_phone_show').html(fullPhone + '&nbsp;&nbsp;');
            return true;
        }
    });

    //edit - add_phone
    $(document).on('click', '.edit', function()
    {
        $('.get_code').removeClass("get_code").addClass("next").html(nextText);
        $('.static_phone_show').html('');
        $(this).hide();
        $('#subheader_text').html(phoneNumberText);
        $('.phone_group').show();
    });


    //get_code
    $(document).on('click', '.get_code', function()
    {
        $('.modal-title').html(verifyPhoneText);
        $('.phone_group').hide();
        $('.static_phone_show').html('');

        $('#subheader_text').html(smsCodeSentText+ '<br><br>' + smsCodeSubmitText);

        $('#subheader_text').html(smsCodeSentText+ '<br><br>' + smsCodeSubmitText);

        $('.edit').hide();
        $('#phone_verification_code').show().val('');
        $(this).removeClass("get_code").addClass("verify").html(verifyText);

        var pluginCarrierCode = $('#phone').intlTelInput('getSelectedCountryData').dialCode;
        var pluginPhone = $("#phone").intlTelInput("getNumber");

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: SITE_URL+"/profile/getVerificationCode",
            dataType: "json",
            cache: false,
            data: {
                'phone': pluginPhone,
                'carrierCode': pluginCarrierCode,
            }
        })
        .done(function(response)
        {
            if (response.status == true) {
                $('#hasVerificationCode').val(response.message);
            }
        });
    });

    //verify
    $(document).on('click', '.verify', function()
    {
        var classOfSubmit = $('#common_button');
        var phone_verification_code = $("#phone_verification_code").val();

        var pluginPhone = $("#phone").intlTelInput("getNumber");
        var pluginCarrierCode = $('#phone').intlTelInput('getSelectedCountryData').dialCode;
        var pluginDefaultCountry = $('#phone').intlTelInput('getSelectedCountryData').iso2;

        if (classOfSubmit.hasClass('verify')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: SITE_URL+"/profile/complete-phone-verification",
                dataType: "json",
                cache: false,
                data: {
                    'phone': pluginPhone,
                    'defaultCountry': pluginDefaultCountry,
                    'carrierCode': pluginCarrierCode,
                    'phone_verification_code': phone_verification_code,
                }
            })
            .done(function(data)
            {
                if (data.status == false || data.status == 500) {
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.error);
                } else {
                    $('#message').removeClass('alert-danger');
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.success);

                    $('#subheader_text').hide();
                    $('#phone_verification_code').hide();
                    $('#common_button').hide();
                    $('#close').hide();
                    $('.modal-title').hide();
                }
            });
        }
    });

    //form_submit
    $(document).on('click', '.form_submit', function()
    {
        var classOfSubmit = $('#common_button');
        if (classOfSubmit.hasClass('form_submit')) {
            var pluginPhone = $("#phone").intlTelInput("getNumber");
            var pluginDefaultCountry = $('#phone').intlTelInput('getSelectedCountryData').iso2;
            var pluginCarrierCode = $('#phone').intlTelInput('getSelectedCountryData').dialCode;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: SITE_URL+"/profile/add-phone-number",
                dataType: "json",
                cache: false,
                data: {
                    'phone': pluginPhone,
                    'defaultCountry': pluginDefaultCountry,
                    'carrierCode': pluginCarrierCode,
                }
            })
            .done(function(data)
            {
                if (data.status == true) {
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.class_name);

                    $('#subheader_text').hide();
                    $('#common_button').hide();
                    $('#close').hide();
                    $('.phone_group').hide();
                }
            });
        }
    });


    //clear inputs on close - edit modal
    $('#editModal').on('hidden.bs.modal', function () {
        if ($("#edit_phone").val() != '') {
            $("#edit_phone").val(`+${OrginalUsercarrierCode}${OrginalUserphone}`)
            //need to reload - or validation message still exists.
            window.location.reload(); 
        }
    });

    $(document).ready(function()
    {
        $("#edit_phone").intlTelInput({
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
            if (formattedPhone !== null && carrierCode !== null && defaultCountry !== null) {
                $("#edit_phone").intlTelInput("setNumber", formattedPhone);
                $('#edit_defaultCountry').val(defaultCountry);
                $('#edit_carrierCode').val(carrierCode);
            }
        });
    });

    var editCountryData = $("#edit_phone").intlTelInput("getSelectedCountryData");
    $("#edit_phone").on("countrychange", function(e, editCountryData)
    {
        $('#edit_defaultCountry').val(editCountryData.iso2);
        $('#edit_carrierCode').val(editCountryData.dialCode);

        if ($.trim($(this).val())) {
            if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
                $('#edit-tel-number-error').addClass('error').html(validPhoneNumberErrorText).css({
                    'color' : '#f50000 !important',
                    'font-size' : '14px',
                    'font-weight' : '400',
                    'padding-top' : '5px',
                });
                $('#common_button_update').prop('disabled',true);
                $('#edit-phone-number-error').hide();
            } else {
                $('#edit-tel-number-error').html('');

                var id = $('#id').val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: SITE_URL+"/profile/duplicate-phone-number-check",
                    dataType: "json",
                    cache: false,
                    data: {
                        'phone': $.trim($(this).val()),
                        'carrierCode': $.trim(countryData.dialCode),
                        'id': id,
                    }
                })
                .done(function(response)
                {
                    if (response.status == true) {
                        $('#edit-tel-number-error').html('');
                        $('#edit-phone-number-error').show();

                        $('#edit-phone-number-error').addClass('error').html(response.fail).css("font-weight", "bold");
                        $('#common_button_update').prop('disabled',true);
                    } else if (response.status == false) {
                        $('#edit-tel-number-error').show();
                        $('#edit-phone-number-error').html('');

                        $('#common_button_update').prop('disabled',false);
                    }
                });
            }
        } else {
            $('#edit-tel-number-error').html('');
            $('#edit-phone-number-error').html('');
            $('#common_button_update').prop('disabled',false);
        }
    });

    //Invalid Number Validation - user edit
    $(document).ready(function()
    {
        $("#edit_phone").on('blur', function(e)
        {
            if ($.trim($(this).val())) {
                if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
                    $('#edit-tel-number-error').addClass('error').html(validPhoneNumberErrorText).css({
                        'color' : '#f50000 !important',
                        'font-size' : '14px',
                        'font-weight' : '400',
                        'padding-top' : '5px',
                    });
                    $('#common_button_update').prop('disabled',true);
                    $('#edit-phone-number-error').hide();
                } else {
                    var id = $('#user_id').val();

                    var phone = $(this).val().replace(/-|\s/g,""); //replaces 'whitespaces', 'hyphens'
                    var phone = $(this).val().replace(/^0+/,"");  //replaces (leading zero - for BD phone number)

                    var pluginCarrierCode = $(this).intlTelInput('getSelectedCountryData').dialCode;

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "POST",
                        url: SITE_URL+"/profile/duplicate-phone-number-check",
                        dataType: "json",
                        cache: false,
                        data: {
                            'id': id,
                            'phone': phone,
                            'carrierCode': $.trim(pluginCarrierCode),
                        }
                    })
                    .done(function(response)
                    {
                        if (response.status == true) {
                            if (phone.length == 0) {
                                $('#edit-phone-number-error').html('');
                            } else {
                                $('#edit-phone-number-error').addClass('error').html(response.fail).css({
                                    'color' : '#f50000 !important',
                                    'font-size' : '14px',
                                    'font-weight' : '400',
                                    'padding-top' : '5px',
                                });
                                $('#common_button_update').prop('disabled',true);
                            }
                        } else if (response.status == false) {
                            $('#common_button_update').prop('disabled',false);
                            $('#edit-phone-number-error').html('');
                        }
                    });
                    $('#edit-tel-number-error').html('');
                    $('#edit-phone-number-error').show();
                    $('#common_button_update').prop('disabled',false);
                }
            } else {
                $('#edit-tel-number-error').html('');
                $('#edit-phone-number-error').html('');
                $('#common_button_update').prop('disabled',false);
            }
        });
    });

    //when phone verificaiton is enabled
    $(document).on('click', '.update', function()
    {
        var phone = $("input[name=edit_phone]");
        if (phone.val() == '') {
            $('#edit-phone-number-error').addClass('error').html(fieldRequiredText).css({
                'color' : '#f50000 !important',
                'font-size' : '14px',
                'font-weight' : '400',
                'padding-top' : '5px',
            });
            return false;
        } else if(phone.hasClass('error')) {
            return false;
        } else {
            $('.modal-title').html(getCodeText);

            $('#subheader_edit_text').html(verificationCodeText);

            $('.phone_group').hide();

            $('#edit_static_phone_show').show();

            $('.edit_button_edit').show();

            $(this).removeClass("update").addClass("edit_get_code").html(getCodeText);

            var edit_phone = $("#edit_phone").intlTelInput("getNumber");
            $('#edit_static_phone_show').html(edit_phone + '&nbsp;&nbsp;');
            return true;
        }
    });

    //edit_get_code
    $(document).on('click', '.edit_get_code', function()
    {
        $('.modal-title').html(verifyPhoneText);
        $(this).removeClass("edit_get_code").addClass("edit_verify").html(verifyText);
        $('.phone_group').hide();
        $('.edit_button_edit').hide();
        $('.edit_static_phone_show').html('');
        $('#subheader_edit_text').html(smsCodeSentText + '<br><br>' + smsCodeSubmitText);
        $('#edit_phone_verification_code').show().val('');

        var pluginPhone = $("#edit_phone").intlTelInput("getNumber");
        var pluginCarrierCode = $('#edit_phone').intlTelInput('getSelectedCountryData').dialCode;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: SITE_URL+"/profile/editGetVerificationCode",
            dataType: "json",
            cache: false,
            data: {
                'phone': pluginPhone,
                'code': pluginCarrierCode,
            }
        })
        .done(function(response)
        {
            if (response.status == true) {
                $('#editHasVerificationCode').val(response.message);
            }
        });
    });

    //edit_verify
    $(document).on('click', '.edit_verify', function()
    {
        var classOfSubmit = $('#common_button_update');
        var edit_phone_verification_code = $("#edit_phone_verification_code").val();
        var pluginPhone = $("#edit_phone").intlTelInput("getNumber");
        var pluginDefaultCountry = $('#edit_phone').intlTelInput('getSelectedCountryData').iso2;
        var pluginCarrierCode = $('#edit_phone').intlTelInput('getSelectedCountryData').dialCode;


        if (classOfSubmit.hasClass('edit_verify')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: SITE_URL+"/profile/edit-complete-phone-verification",
                dataType: "json",
                cache: false,
                data: {
                    'phone': pluginPhone,
                    'flag': pluginDefaultCountry,
                    'code': pluginCarrierCode,
                    'edit_phone_verification_code': edit_phone_verification_code,
                }
            })
            .done(function(data)
            {
                if (data.status == false || data.status == 500) {
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.error);
                } else {
                    $('#message').removeClass('alert-danger');
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.success);

                    $('#subheader_edit_text').hide();
                    $('#edit_phone_verification_code').hide();
                    $('#common_button_update').hide();
                    $('#close').hide();
                    $('.modal-title').hide();
                }
            });
        }
    });

    //when phone verificaiton is disabled
    $(document).on('click', '.edit_form_submit', function()
    {
        var classOfSubmit = $('#common_button_update');
        if (classOfSubmit.hasClass('edit_form_submit')) {
            var pluginPhone = $("#edit_phone").intlTelInput("getNumber");
            var pluginDefaultCountry = $('#edit_phone').intlTelInput('getSelectedCountryData').iso2;
            var pluginCarrierCode = $('#edit_phone').intlTelInput('getSelectedCountryData').dialCode;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: SITE_URL+"/profile/update-phone-number",
                dataType: "json",
                cache: false,
                data: {
                    'phone': pluginPhone,
                    'flag': pluginDefaultCountry,
                    'code': pluginCarrierCode,
                }
            })
            .done(function(data)
            {
                if (data.status == true) {
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.class_name);

                    $('#subheader_edit_text').hide();
                    $('#common_button_update').hide();
                    $('#close').hide();
                    $('.phone_group').hide();
                    $('.modal-title').hide();
                }
            });
        }
    });

    //start - ajax image upload
    $('#file').change(function () {
        if ($(this).val() != '') {
            upload(this);
        }
    });
    function upload(img) {
        var form_data = new FormData();
        form_data.append('file', img.files[0]);
        form_data.append('_token', csrfToken);
        $('#loading').css('display', 'block');
        $.ajax({
            url: profileImageUploadUrl,
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            success: function (data) {
                if (data.fail) {
                    $('#file-error').show().addClass('error').html(data.errors.file).css({
                        'color' : '#f50000 !important',
                        'font-size' : '14px',
                        'font-weight' : '400',
                        'padding-top' : '5px',
                    });
                } else {
                    $('#file-error').hide();
                    $('#file_name').val(data);
                    $('#profileImage').attr('src', profileImageSourceUrl + '/' + data);
                    $('#profileImageHeader').attr('src', profileImageSourceUrl + '/' + data);
                    $('#profileImageHeaderdrop').attr('src', profileImageSourceUrl + '/' + data);
                    location.reload();
                }
                $('#loading').css('display', 'none');
            },
            error: function (xhr, status, error) {
            }
        });
    }
        

    jQuery.extend(jQuery.validator.messages, {
        required: fieldRequiredText,
        minlength: $.validator.format(minPasswordCharacterLength.replace(':x', '{0}')),
        alpha: nameError,
        maxlength: nameLengthError
    })

    $("#reset_password").validate({
        rules: {
            old_password: {
                required: true
            },
            password: {
                required: true,
                minlength: 6,
            },
            confirm_password: {
                equalTo: "#password",
                minlength: 6,
            }
        },
        messages: {
            password: {
                required: fieldRequiredText
            },
            confirm_password: {
                equalTo: passwordSameValue
            },
        }
    });

    $('#profile_update_form').validate({
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
        },
        submitHandler: function(form)
        {
            $("#users_profile").attr("disabled", true);
            $(".spinner").show();
            $("#users_profile_text").text(submittingText);
            form.submit();
        }
    });

    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    });
}