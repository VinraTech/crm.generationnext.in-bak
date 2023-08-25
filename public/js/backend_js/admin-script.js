jQuery(document).ready(function() { 
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });    
    TableAjax.init();
    $(document).on('click','.toogle_switch',function(){
        if($(this).hasClass('bootstrap-switch-on')){
            $(this).removeClass('bootstrap-switch-on');
            $(this).addClass('bootstrap-switch-off');
            var status=0;
            var id_sent=$(this).attr('id');
        }
        else{
            $(this).removeClass('bootstrap-switch-off');
            $(this).addClass('bootstrap-switch-on');
            var status=1;
            var id_sent=$(this).attr('id');
        }
        var table = $(this).attr('rel');
        var ajax_url='status';
        $.ajax({
            url:ajax_url,
            type:'POST',
            data:{
                'id':id_sent,'status':status, 'table':table
            },
            success:function(msg) {
            }
        })
    });

    $(".form-filter").keypress(function(event) {
        if (event.keyCode === 13) {
            $(".filter-submit").click();
        }
    });

    $(".select").change(function(event) {
        $(".filter-submit").click();
    });

    $('#change_pass').formValidation({
        framework: 'bootstrap',
        message: 'This value is not valid',
        icon:{
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "password":{
                validators:{
                    notEmpty:{
                        message: 'Current password is required'
                    },
                    remote:{
                        message: 'Current password is incorrect',
                        url: '/s/admin/checkAdminPassword',
                        type: 'POST',
                        delay: 1000     // Send Ajax request every 2 seconds
                    }
                }
            },
            "new_password":{
                validators:{
                    notEmpty:{
                        message: 'New password is required'
                    }
                }
            },
            "re_password":{
                validators:{
                    notEmpty:{
                        message: 'Confirm Password  is required'
                    },
                    identical:{
                        field: "new_password",
                        message: 'Confirm Password is not match with New Password'
                    }
                }
            }
        }
    });

    /*Employee Validation starts*/
    $('#addEditEmployee').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "type":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "parent_id":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "products[]":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "state":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "city":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            // "address":{
            //     validators:{
            //         notEmpty:{
            //             message: 'This field is required'
            //         },
            //     }
            // },
            // "dob":{
            //     validators:{
            //         notEmpty:{
            //             message: 'This field is required'
            //         },
            //     }
            // },
            // "doj":{
            //     validators:{
            //         notEmpty:{
            //             message: 'This field is required'
            //         },
            //     }
            // },
            // "pan":{
            //     validators:{
            //         notEmpty:{
            //             message: 'This field is required'
            //         },
            //         stringLength: {
            //             min:10,
            //             max: 10,
            //             message: 'Pan Number must be 10 digits only'
            //         },
            //         regexp: {
            //             regexp: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/,
            //             message: 'For pan number first 5 characters should be uppercase alphabets next 4 characters should be only digits and last one should be an uppercase alphabet.'
            //         }
            //     }
            // },
            "adhaar_no":{
                validators:{
                    
                     stringLength: {
                        min:12,
                        max: 12,
                        message: 'Aadhaar Number must be 12 digits only'
                    },
                    regexp: {
                        regexp: /^[2-9]{1}[0-9]{11}$/,
                        message: 'Aadhaar number shoulnot start with 0 or 1.'
                    }
                }
            },
            "designation_name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "monthly_salary":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Name must be a string only.'
                    }
                    
                }


            },
            // "address":{
            //     validators:{
            //         regexp: {
            //             regexp: /^[a-zA-Z\s]+$/,
            //             message: 'Employee Address must be a string only.'
            //         }

            //     }

            // },
            "blood_group":{
                validators:{
                    regexp: {
                        regexp: /^([^0-9]*)$/,
                        message: 'Digits are not allowed.'
                    }

                }

            },
            "bank_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Bank Name must be a string only.'
                    }

                }

            },
            "bank_details":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Bank Details must be a string only.'
                    }

                }

            },
            "pcc":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'PCC must be a string only.'
                    }

                }

            },
            "medical_status":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Medical Status must be a string only.'
                    }

                }

            },
            
            "pan":{
                validators:{
                    regexp: {
                        regexp: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/,
                        message: 'For PAN Number first 5 characters should be uppercase alphabets, next four characters should be digits and last one should be an uppercase alphabet.'
                    }

                }

            },
            "adhaar_no":{
                validators:{
                    stringLength: {
                        min:12,
                        max: 12,
                        message: 'Aadhaar Number must be 12 digits only'
                    },
                    regexp: {
                        regexp: /^[2-9]{1}[0-9]{11}$/,
                        message: 'Aadhaar number shoulnot start with 0 or 1.'
                    }

                }

            },
            
            "emergency_number":{
                validators:{
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Emergency Mobile Number must be 10 digits only'
                    },
                    
                }

            },
            "email":{
                validators:{
                    notEmpty:{
                        message: 'Email is required.'
                    },
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    },
                    remote:{
                        message: 'This email already exists.',
                        url: '/s/admin/checkEmployeeEmail',
                        type: 'POST',
                        delay: 2000     // Send Ajax request every 2 seconds
                    }
                }
            },
            "mobile":{
                validators:{   
                    notEmpty:{
                        message: 'Mobile Number is required.'
                    },
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Mobile Number must be 10 digits only'
                    },
                    numeric: {
                        message: 'Mobile Number must be in digits only',
                        transformer: function($field, validatorName, validator) {
                            var value = $field.val();
                            return value.replace(',', '');
                        }
                    }
                }
            },
            "password":{
                validators: {
                    notEmpty: {
                        message: 'The password is required and cannot be empty'
                    },
                    callback: {
                        callback: function(value, validator, $field) {
                            var score = 0;

                            if (value === '') {
                                return {
                                    valid: true,
                                    score: null
                                };
                            }

                            // Check the password strength
                            score += ((value.length >= 8) ? 1 : -1);

                            // The password contains uppercase character
                            if (/[A-Z]/.test(value)) {
                                score += 1;
                            }

                            // The password contains uppercase character
                            if (/[a-z]/.test(value)) {
                                score += 1;
                            }

                            // The password contains number
                            if (/[0-9]/.test(value)) {
                                score += 1;
                            }

                            // The password contains special characters
                            if (/[!#$%&^~*_]/.test(value)) {
                                score += 1;
                            }

                            return {
                                valid: true,
                                score: score    // We will get the score later
                            };
                        }
                    }
                }
            },
        }
    })
    .on('success.validator.fv', function(e, data) {
        // The password passes the callback validator
        if (data.field === 'password' && data.validator === 'callback') {
            // Get the score
            var score = data.result.score,
                $bar  = $('#passwordMeter').find('.progress-bar');

            switch (true) {
                case (score === null):
                    $bar.html('').css('width', '0%').removeClass().addClass('progress-bar');
                    break;

                case (score <= 0):
                    $bar.html('Very weak').css('width', '25%').removeClass().addClass('progress-bar progress-bar-danger');
                    break;

                case (score > 0 && score <= 2):
                    $bar.html('Weak').css('width', '50%').removeClass().addClass('progress-bar progress-bar-warning');
                    break;

                case (score > 2 && score <= 4):
                    $bar.html('Medium').css('width', '75%').removeClass().addClass('progress-bar progress-bar-info');
                    break;

                case (score > 4):
                    $bar.html('Strong').css('width', '100%').removeClass().addClass('progress-bar progress-bar-success');
                    break;

                default:
                    break;
            }
        }
    })
    .on('err.field.fv', function(e, data) {
        data.fv.disableSubmitButtons(false);
    })
    .on('success.field.fv', function(e, data) {
        data.fv.disableSubmitButtons(false);
    });


    //Edit Employee
    $('#editEmployee').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "type":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "parent_id":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "products[]":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "state":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "city":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "adhaar_no":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                     stringLength: {
                        min:12,
                        max: 12,
                        message: 'Aadhaar Number must be 12 digits only'
                    },
                    regexp: {
                        regexp: /^[2-9]{1}[0-9]{11}$/,
                        message: 'Aadhaar number shoulnot start with 0 or 1.'
                    }
                }
            },
            "designation_name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "monthly_salary":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Name must be a string only.'
                    }
                    
                }


            },
            "blood_group":{
                validators:{
                    regexp: {
                        regexp: /^([^0-9]*)$/,
                        message: 'Digits are not allowed.'
                    }

                }

            },
            "bank_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Bank Name must be a string only.'
                    }

                }

            },
            "bank_details":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Bank Details must be a string only.'
                    }

                }

            },
            "pcc":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'PCC must be a string only.'
                    }

                }

            },
            "medical_status":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Medical Status must be a string only.'
                    }

                }

            },
            
            "pan":{
                validators:{
                    regexp: {
                        regexp: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/,
                        message: 'For PAN Number first 5 characters should be uppercase alphabets, next four characters should be digits and last one should be an uppercase alphabet.'
                    }

                }

            },
            "adhaar_no":{
                validators:{
                    stringLength: {
                        min:12,
                        max: 12,
                        message: 'Aadhaar Number must be 12 digits only'
                    },
                    regexp: {
                        regexp: /^[2-9]{1}[0-9]{11}$/,
                        message: 'Aadhaar number shoulnot start with 0 or 1.'
                    }

                }

            },
            
            "emergency_number":{
                validators:{
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Emergency Mobile Number must be 10 digits only'
                    },
                    
                }

            },
            "email":{
                validators:{
                    notEmpty:{
                        message: 'Email is required.'
                    },
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
            "mobile":{
                validators:{   
                    notEmpty:{
                        message: 'Mobile Number is required.'
                    },
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Mobile Number must be 10 digits only'
                    },
                    numeric: {
                        message: 'Mobile Number must be in digits only',
                        transformer: function($field, validatorName, validator) {
                            var value = $field.val();
                            return value.replace(',', '');
                        }
                    }
                }
            },
            "password":{
                validators: {
                    notEmpty: {
                        message: 'The password is required and cannot be empty'
                    },
                    callback: {
                        callback: function(value, validator, $field) {
                            var score = 0;

                            if (value === '') {
                                return {
                                    valid: true,
                                    score: null
                                };
                            }

                            // Check the password strength
                            score += ((value.length >= 8) ? 1 : -1);

                            // The password contains uppercase character
                            if (/[A-Z]/.test(value)) {
                                score += 1;
                            }

                            // The password contains uppercase character
                            if (/[a-z]/.test(value)) {
                                score += 1;
                            }

                            // The password contains number
                            if (/[0-9]/.test(value)) {
                                score += 1;
                            }

                            // The password contains special characters
                            if (/[!#$%&^~*_]/.test(value)) {
                                score += 1;
                            }

                            return {
                                valid: true,
                                score: score    // We will get the score later
                            };
                        }
                    }
                }
            },
        }
    })
    .on('success.validator.fv', function(e, data) {
        // The password passes the callback validator
        if (data.field === 'password' && data.validator === 'callback') {
            // Get the score
            var score = data.result.score,
                $bar  = $('#passwordMeter').find('.progress-bar');

            switch (true) {
                case (score === null):
                    $bar.html('').css('width', '0%').removeClass().addClass('progress-bar');
                    break;

                case (score <= 0):
                    $bar.html('Very weak').css('width', '25%').removeClass().addClass('progress-bar progress-bar-danger');
                    break;

                case (score > 0 && score <= 2):
                    $bar.html('Weak').css('width', '50%').removeClass().addClass('progress-bar progress-bar-warning');
                    break;

                case (score > 2 && score <= 4):
                    $bar.html('Medium').css('width', '75%').removeClass().addClass('progress-bar progress-bar-info');
                    break;

                case (score > 4):
                    $bar.html('Strong').css('width', '100%').removeClass().addClass('progress-bar progress-bar-success');
                    break;

                default:
                    break;
            }
        }
    })
    .on('err.field.fv', function(e, data) {
        data.fv.disableSubmitButtons(false);
    })
    .on('success.field.fv', function(e, data) {
        data.fv.disableSubmitButtons(false);
    });

    /*Employee Validation ends*/
    
    //Designation Validation

    $('#addEditDesignationform').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "designation_name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Designation Name must be a string only.'
                    }
                    
                }


            }
        }
    });


    //DOB DATEPICKER
    $('.dobDatepicker').datetimepicker({
        format:'YYYY-MM-DD',
        useCurrent: false,
        allowInputToggle: true
    });

    $('.dobDatepicker_emp').datetimepicker({
        format:'DD-MM-YYYY',
        useCurrent: false,
        allowInputToggle: true,
        
    });

    $('.clientDatepicker').datetimepicker({
        format:'DD/MM/YYYY',
        useCurrent: false,
        allowInputToggle: true
    });

    /*Get Employee Information starts*/
    $(document).on('click','.getEmpid',function(){
        $('.loadingDiv').show();
        var empid = $(this).attr('id');
        $.ajax({
            type: "post",
            url: "/s/admin/get-emp-details",
            data: {id : empid},
            success:function(resp){
                $('.loadingDiv').hide();
                $('#appendemployeedata').html(resp);
                $('#ViewEmployeeDetails').modal('show');
            },
            error:function(){}
        })
    })
    /*Get Employee Information ends*/

    /*Employee Roles Scripts starts*/
    $(document).on('change','.getModuleid',function(){
        var roleType = $(this).attr('data-attr');
        var id = $(this).attr('rel');
        if(roleType === "View"){
            $('#edit-'+id).prop('checked',false);
            $('#delete-'+id).prop('checked',false);
        }else if(roleType==="Edit"){
            $('#view-'+id).prop('checked',true);
            $('#delete-'+id).prop('checked',false);
        }else if(roleType==="Delete"){
            $('#view-'+id).prop('checked',true);
            $('#edit-'+id).prop('checked',true);
        }
    });
    /*Employee Roles Scripts ends*/

    $('#AddleadForm').formValidation({
        framework: 'bootstrap',
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "department":{
                validators:{
                    notEmpty:{
                        message: 'Department is required'
                    }
                }
            },
            "company_name":{
                validators:{
                    notEmpty:{
                        message: 'Company Name is required'
                    }
                }
            },
            "contact_person":{
                validators:{
                    notEmpty:{
                        message: 'Contact Person is required'
                    }
                }
            },
            "product":{
                validators:{
                    notEmpty:{
                        message: 'Product is required'
                    }
                }
            },
            "profile":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    }
                }
            },
            "priority":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    }
                }
            },
            "appoint_date_time":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    }
                }
            },

            "last_status":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    }
                }
            },
            "lead_type":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    }
                }
            },
            "loan_amt":{
                validators:{
                    notEmpty:{
                        message: 'Loan Amount is required'
                    },
                    numeric: {
                        message: 'The Loan Amount must be a number',
                        transformer: function($field, validatorName, validator) {
                            var value = $field.val();
                            return value.replace(',', '');
                        }
                    }
                }
            },
            "phone_no":{
                validators:{
                    stringLength: {
                        min:10,
                        max: 12,
                        message: 'Phone Number must be 10 to 12 digits only'
                    },
                    integer: {
                        message: 'Phone Number be in digits only'
                    }
                }
            },
            "cell_no":{
                validators:{
                    stringLength: {
                        min:10,
                        max: 12,
                        message: 'Cell Number must be 10 to 12 digits only'
                    },
                    integer: {
                        message: 'Cell Number be in digits only'
                    }
                }
            }
        }
    })
    .on('dp.change', function(e) {
        // Revalidate the date field
        $('#AddleadForm').formValidation('revalidateField', 'appoint_date_time');
    });

    //LeadAppointment Date & Time
    $('.leadAppointmentDateTime').find('input').datetimepicker({
        format:'YYYY-MM-DD HH:mm:ss',
        sideBySide:true,
    });
    $('.leadAppointmentDateTime').find('span.glyphicon').on('click', function() {
        $('.leadAppointmentDateTime').find('input').trigger('focus');
    });

    //Schedule Date
    $('.s_date').find('input').datetimepicker({
        format:'YYYY-MM-DD',
        sideBySide:true,
    });
    $('.s_date').find('span.glyphicon').on('click', function() {
        $('.s_date').find('input').trigger('focus');
    });

    $(document).on('click','.getLeadDetails',function(){
        $('.loadingDiv').show();
        var leadid = $(this).data('leadid');
        var companyname = $(this).data('companyname');
        $.ajax({
            type : 'post',
            url  : '/s/admin/get-lead-details',
            data : {leadid :leadid},
            dataType:'json',
            success:function(resp){
                $('#LeadModalTitle').text(companyname+' - '+resp.leadid);
                $("#AppendLeadData").html(resp.leadDetails);
                $("#AppendAllocateLeadData").html(resp.allocateleads);
                $("#LeadModal").modal('show');
                $('.loadingDiv').hide();
            },
            error:function(){
            }
        });
    });

    /*$('#AllocateLeadForm').formValidation({
        framework: 'bootstrap',
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "allocate_to":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    }
                }
            },
        }
    });*/

    //Channel Partner Validation
    $('#addEditChannelPartner').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "type":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "state":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "city":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            
            "emp_id":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Name must be a string only.'
                    }
                }
            },
            "company_name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Company Name must be a string only.'
                    }
                }
            },
            // "pan":{
            //     validators:{
            //         notEmpty:{
            //             message: 'This field is required'
            //         },
            //         stringLength: {
            //             min:10,
            //             max: 10,
            //             message: 'Pan Number must be 10 digits only'
            //         },
            //         regexp: {
            //             regexp: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/,
            //             message: 'For pan number first 5 characters should be uppercase alphabets next 4 characters should be only digits and last one should be an uppercase alphabet.'
            //         }
            //     }
            // },
            "adhaar_no":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                     stringLength: {
                        min:12,
                        max: 12,
                        message: 'Aadhaar Number must be 12 digits only'
                    },
                    regexp: {
                        regexp: /^[2-9]{1}[0-9]{11}$/,
                        message: 'Aadhaar number shoulnot start with 0 or 1.'
                    }
                }
            },
            
            "email":{
                validators:{
                    notEmpty:{
                        message: 'Email is required.'
                    },
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    },
                    remote:{
                        message: 'This email already exists.',
                        url: '/s/admin/checkPartnerEmail',
                        type: 'POST',
                        delay: 2000     // Send Ajax request every 2 seconds
                    }
                }
            },
            "mobile":{
                validators:{   
                    notEmpty:{
                        message: 'Mobile Number is required.'
                    },
                    regexp: {
                        regexp: /^[789]\d{9}$/,
                        message: 'Mobile number must be in digists and 10 in length'
                    }
                }
            },
            "password":{
                validators: {
                    notEmpty: {
                        message: 'The password is required and cannot be empty'
                    },
                    callback: {
                        callback: function(value, validator, $field) {
                            var score = 0;

                            if (value === '') {
                                return {
                                    valid: true,
                                    score: null
                                };
                            }

                            // Check the password strength
                            score += ((value.length >= 8) ? 1 : -1);

                            // The password contains uppercase character
                            if (/[A-Z]/.test(value)) {
                                score += 1;
                            }

                            // The password contains uppercase character
                            if (/[a-z]/.test(value)) {
                                score += 1;
                            }

                            // The password contains number
                            if (/[0-9]/.test(value)) {
                                score += 1;
                            }

                            // The password contains special characters
                            if (/[!#$%&^~*_]/.test(value)) {
                                score += 1;
                            }

                            return {
                                valid: true,
                                score: score    // We will get the score later
                            };
                        }
                    }
                }
            },
        }
    })
    .on('success.validator.fv', function(e, data) {
        // The password passes the callback validator
        if (data.field === 'password' && data.validator === 'callback') {
            // Get the score
            var score = data.result.score,
                $bar  = $('#passwordMeter').find('.progress-bar');

            switch (true) {
                case (score === null):
                    $bar.html('').css('width', '0%').removeClass().addClass('progress-bar');
                    break;

                case (score <= 0):
                    $bar.html('Very weak').css('width', '25%').removeClass().addClass('progress-bar progress-bar-danger');
                    break;

                case (score > 0 && score <= 2):
                    $bar.html('Weak').css('width', '50%').removeClass().addClass('progress-bar progress-bar-warning');
                    break;

                case (score > 2 && score <= 4):
                    $bar.html('Medium').css('width', '75%').removeClass().addClass('progress-bar progress-bar-info');
                    break;

                case (score > 4):
                    $bar.html('Strong').css('width', '100%').removeClass().addClass('progress-bar progress-bar-success');
                    break;

                default:
                    break;
            }
        }
    })
    .on('err.field.fv', function(e, data) {
        data.fv.disableSubmitButtons(false);
    })
    .on('success.field.fv', function(e, data) {
        data.fv.disableSubmitButtons(false);
    });
   
    

    /*Get Channel Partner Information starts*/
    $(document).on('click','.getPartnerid',function(){
        $('.loadingDiv').show();
        var partnerid = $(this).attr('id');
        $.ajax({
            type: "post",
            url: "/s/admin/get-partner-details",
            data: {id : partnerid},
            success:function(resp){
                $('.loadingDiv').hide();
                $('#appendemployeedata').html(resp);
                $('#ViewEmployeeDetails').modal('show');
            },
            error:function(){}
        })
    })
    /*Get Employee Information ends*/

    //States and Cities
    $(document).on('change','.getState',function(){
        $('.loadingDiv').show();
        var stateval= $(this).val();
        if(stateval !=""){
            var stateid = $(this).find(':selected').data('stateid');
            $.ajax({
                url : '/s/admin/get-cities',
                data : {stateid: stateid},
                type : 'post',
                success:function(resp){
                    $("#AppendCities").html(resp);
                    $("#AppendPermCities").html(resp);
                    $('.loadingDiv').hide();
                },
                error:function(){}
            });
        }else{
            $("#AppendCities").html('');
            $("#AppendPermCities").html('');
            $('.loadingDiv').hide();
        }
    });
    $(document).on('change','.getPermState',function(){
        $('.loadingDiv').show();
        var stateval= $(this).val();
        if(stateval !=""){
            var stateid = $(this).find(':selected').data('stateid');
            $.ajax({
                url : '/s/admin/get-cities',
                data : {stateid: stateid},
                type : 'post',
                success:function(resp){
                    
                    $("#AppendPermCities").html(resp);
                    $('.loadingDiv').hide();
                },
                error:function(){}
            });
        }else{
            
            $("#AppendPermCities").html('');
            $('.loadingDiv').hide();
        }
    });

    //AJAX ON LEAD TYPE (Direct/Indirect)
    $(document).on('change','.getleadType',function(){
        $(".loadingDiv").show();
        var leadtype = $(this).val();
        if(leadtype=="Indirect"){
            $.ajax({
                type : "post",
                url :'/s/admin/append-indirect-details?type=crm',
                success:function(resp){
                    $("#AppendCrm").html(resp);
                    $(".loadingDiv").hide();
                    $option = "crm_id";
                    $('#AddleadForm').formValidation('addField', $option, {
                        validators:{   
                            notEmpty:{
                                message: 'This field is required.'
                            },
                        }
                    });
                    $("#AppendCrm").addClass('in');
                },
                error:function(){}
            })
        }else{
            $find = $('.form-group');
            if($("#AppendCrm").length > 0){
                $('#AddleadForm')
                .formValidation('removeField', $find.find('[name="crm_id"]'));
                $("#AppendCrm").remove();
                var $target = $('#SelectAppender');
                $target.after('<div class="form-group collapse" id="AppendCrm"></div>');
            }
            if($("#AppendChannelPartners").length > 0){
                $('#AddleadForm')
                .formValidation('removeField', $find.find('[name="channel_partner_id"]'));
                $("#AppendChannelPartners").remove();
                var $target = $('#AppendCrm');
                $target.after('<div class="form-group collapse" id="AppendChannelPartners"></div>');
            }
            $(".loadingDiv").hide();
        }
    });

    $(document).on('change','.getCrm',function(){
        $(".loadingDiv").show();
        var crmid = $(this).val();
        $.ajax({
            type : "post",
            url :'/s/admin/append-indirect-details?type=partners&crmid='+crmid+'',
            success:function(resp){
                $("#AppendChannelPartners").html(resp);
                $(".loadingDiv").hide();
                $option = "channel_partner_id";
                $('#AddleadForm').formValidation('addField', $option, {
                    validators:{   
                        notEmpty:{
                            message: 'This field is required.'
                        },
                    }
                });
                $("#AppendChannelPartners").addClass('in');
            },
            error:function(){}
        })
    });

    /*TODO THREADS*/
    var Todo = function () {
        var _initComponents = function() {
            $('.todo-taskbody-due').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true
            });      
            $(".todo-taskbody-tags").select2({
                tags: ["Testing", "Important", "Info", "Pending", "Completed", "Requested", "Approved"]
            });
        }
        var _handleProjectListMenu = function() {
            if (Metronic.getViewPort().width <= 992) {
                $('.todo-project-list-content').addClass("collapse");
            } else {
                $('.todo-project-list-content').removeClass("collapse").css("height", "auto");
            }
        }
        return {
            init: function () {
                _initComponents();     
                _handleProjectListMenu();

                Metronic.addResizeHandler(function(){
                    _handleProjectListMenu();    
                });       
            }
        };
    }();
    /*TODO THREADS*/

    //View Attached Files
    $('.absAttachmentLink').bind('click', function () {
        var $this = $(this).siblings('.padderDivAttach');
        $this.slideToggle('slow');
    });

    //Get Lead Status
    $(document).on('change','.getLeadStatus',function(){
        $(".loadingDiv").show();
        var status = $(this).val();
        $.ajax({
            url : '/s/admin/append-lead-status-data',
            type : 'post',
            data : {status :status},
            success:function(resp){
                $("#AppendAjaxResp").html(resp);
                $(".loadingDiv").hide();
                if(resp !== ""){
                    $('.appointmentdatetimepicker').find('input').datetimepicker({
                        format:'YYYY-MM-DD HH:mm:ss',
                        sideBySide:true,
                    });
                    $('.appointmentdatetimepicker').find('input').trigger('focus');
                    $('.appointmentdatetimepicker').find('span.glyphicon').on('click', function() {
                        $('.appointmentdatetimepicker').find('input').trigger('focus');
                    });
                }
            },
            error:function(){}
        })
    });

    //Lead Status Validation
    $('#addEditLeadStatusForm').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "type":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "lead_behaviour":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "add_lead_status":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "update_lead_status":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            }
        }
    });

    //Client form adding row

$(document).ready(function(){


  new_row="<tr><td><input type='text' name='reference_name[]' placeholder='Reference Name' style='color:gray' autocomplete='off' class='form-control' /></td><td><input type='text' name='phone_number[]' placeholder='Phone Number' style='color:gray' autocomplete='off' class='form-control'</td><td><input type='text' name='proper_address[]' placeholder='Address' style='color:gray' autocomplete='off' class='form-control'</td></tr>";
  var count=0;
  $("#add_ref").click(function(){
    $("#refernce_list").append(new_row);
    count++;
    if(count == 1){
        $("#add_ref").off('click');

    }
    return false;
  });

   
   $("#add_details").click(function(){
      $('#bankdetails_list').find('tr[data-row]').first().attr('data-row', '');
      var cloned = $('#bankdetails_list').find('tr[data-row]').first().clone();
      $('#bankdetails_list').find('tbody').append(cloned);
       $('[data-row]').each(function (index) {

                $(this).attr('data-row', (index + 1));
               
                count++;
            if(count == 1){

               $("#add_details").off('click');
                $('.addebtn_errs').css({
                         'padding-left' : '15px',
                         'color': 'red',
                         'display' : 'block' 
                }).show();
            }
        });
       $('tr[data-row]:last-of-type').find('input.form-control').each(function () {
                $(this).val('');
       });
       $(".ifs_code").addClass("ifscc");
            $('.ifs_err').addClass("ifscc_err");
            $('.ifscc').on('input', function() {
                
                var inpt = $(this);
                
                var regsx   = /^[A-Z]{4}0[A-Z0-9]{6}$/;
                var istn_ifsc = regsx.test(inpt.val());
                

            if(!istn_ifsc){
               $('.ifscc_err').css({
                'padding-left' : '15px',
                'color': 'red',
                'display' : 'block' 
                }).show();
            }else{
                $('.ifscc_err').css({
                'padding-left' : '15px',
                'color': 'red',
                'display' : 'block' 
                 }).hide();
            }
       

        });
       
    return false;
    });
    $('#bankdetails_list').find('td[data-ifsc]').each(function(){
            $('.ifs_code').on('input', function() {
        
                var data = $(this);
                var regt   = /^[A-Z]{4}0[A-Z0-9]{6}$/;
                var isn_ifsc = regt.test(data.val());

            if(!isn_ifsc){
               $('.ifs_err').css({
                'padding-left' : '15px',
                'color': 'red',
                'display' : 'block' 
                }).show();
            }else{
                $('.ifs_err').css({
                'padding-left' : '15px',
                'color': 'red',
                'display' : 'block' 
                 }).hide();
            }
       

        });
       });
   
    $("#add_bankdetails").click(function(){
     $('#channelbankdetails_list').find('tr[data-row]').first().attr('data-row', '');
        var cloned = $('#channelbankdetails_list').find('tr[data-row]').first().clone();
        $('#channelbankdetails_list').find('tbody').append(cloned);
        $('[data-row]').each(function (index) {
                
                $(this).attr('data-row', (index + 1));
              
                count++;
            if(count == 1){

               $("#add_bankdetails").off('click');
                $('.addbtn_errs').css({
                         'padding-left' : '15px',
                         'color': 'red',
                         'display' : 'block' 
                }).show();
            }
        });
        $('tr[data-row]:last-of-type').find('input.form-control').each(function () {
                $(this).val('');
        });
            $(".ifs_code").addClass("ifscc");
            $('.ifs_err').addClass("ifscc_err");
            $('.ifscc').on('input', function() {
                
                var inp = $(this);
                
                var regs   = /^[A-Z]{4}0[A-Z0-9]{6}$/;
                var ist_ifsc = regs.test(inp.val());
                

            if(!ist_ifsc){
               $('.ifscc_err').css({
                'padding-left' : '15px',
                'color': 'red',
                'display' : 'block' 
                }).show();
            }else{
                $('.ifscc_err').css({
                'padding-left' : '15px',
                'color': 'red',
                'display' : 'block' 
                 }).hide();
            }
       

        });
       
       
    
    return false;
    });
    
$('#channelbankdetails_list').find('td[data-ifsc]').each(function(){
            $('.ifs_code').on('input', function() {
        
                var input = $(this);
                var reg   = /^[A-Z]{4}0[A-Z0-9]{6}$/;
                var is_ifsc = reg.test(input.val());

            if(!is_ifsc){
               $('.ifs_err').css({
                'padding-left' : '15px',
                'color': 'red',
                'display' : 'block' 
                }).show();
            }else{
                $('.ifs_err').css({
                'padding-left' : '15px',
                'color': 'red',
                'display' : 'block' 
                 }).hide();
            }
       

        });
       });
       
    
      
     
     
     var chk_value = $('.pre_addr_chk').is(':checked');
     if(chk_value == true){
         var txtVal = $(".pres_addr").val();
          var txtVal1 = $(".pre_landmark").val();
          var txtVal2 = $(".pre_pincode").val();
          var state = $(".cli_state option:selected").val();
          var city = $(".cli_city option:selected").val();
          $(".cli_state").change(function(){
            var state = $(this).val();
            $('.cli_perm_state').val(state);
          });
          $(".cli_city").change(function(){
           var city = $(this).val();
           $('.cli_perm_city').val(city);
          });
          $(".pres_addr").keyup(function(){
            var txtVal = $(this).val();
            $('.perman_addr').val(txtVal);
          });
          $(".pre_landmark").keyup(function(){
            var txtVal = $(this).val();
            $('.perm_landmark').val(txtVal);
          });
          $(".pre_pincode").keyup(function(){
            var txtVal = $(this).val();
            $('.permt_pincode').val(txtVal);
          });
          $('.perman_addr').val(txtVal);
          $('.perm_landmark').val(txtVal1);
          $('.permt_pincode').val(txtVal2);
          $('.cli_perm_state').val(state);
          $('.cli_perm_city').val(city);
     }      
   
     $(".pre_addr_chk").click(function(){
        var chk = $(this).prop('checked');
        if(chk == true){
          var txtVal = $(".pres_addr").val();
          var txtVal1 = $(".pre_landmark").val();
          var txtVal2 = $(".pre_pincode").val();
          var state = $(".cli_state option:selected").val();
          var city = $(".cli_city option:selected").val();
          $(".cli_state").change(function(){
            var state = $(this).val();
            $('.cli_perm_state').val(state);
          });
          $(".cli_city").change(function(){
           var city = $(this).val();
           $('.cli_perm_city').val(city);
          });
          $(".pres_addr").keyup(function(){
            var txtVal = $(this).val();
            $('.perman_addr').val(txtVal);
          });
          $(".pre_landmark").keyup(function(){
            var txtVal = $(this).val();
            $('.perm_landmark').val(txtVal);
          });
          $(".pre_pincode").keyup(function(){
            var txtVal = $(this).val();
            $('.permt_pincode').val(txtVal);
          });
          $('.perman_addr').val(txtVal);
          $('.perm_landmark').val(txtVal1);
          $('.permt_pincode').val(txtVal2);
          $('.cli_perm_state').val(state);
          $('.cli_perm_city').val(city);
        }else{
          $('.perman_addr').val('');
          $('.perm_landmark').val('');
          $('.permt_pincode').val('');
          $('.cli_perm_state').val('');
          $('.cli_perm_city').val('');
        }
     });

     // $("#pre_addr").hide();
     // $("#pre_addr_chk").hide(); 
    // $("#pre_addrs").on('change',function(){
    //    if($(this).val() == ""){
    //     $("#pre_addr").hide();
    //     $("#pre_addr_chk").hide();
    //    }else{
    //     $("#pre_addr").show();
    //     $("#pre_addr_chk").show();
    //   }
    // });
    // $(".chk_pre").click(function () {
    //         if ($(this).is(":checked")) {
    //             $("#pre_addr").hide();
    //         } else {
    //             $("#pre_addr").show();
    //         }
    // });

    $("#prof").hide();
    $("#profess_other").hide();
    $("#comp").hide();
    $("#comp_type_other").hide();
    $("#buis").hide();
    $("#buis_nat_other").hide();
    $("#comptyp").hide();
    $("#comp_salaried_other").hide();
    $("#inds").hide();
    $("#ind_types").hide();
    $("#mon_salar").hide();
    $("#ann_turn").hide();
    $("#occupt").on('change',function(){
       
      if($(this).val() == "Self Employed Professional"){
        $("#prof").show();
        $("#inds").hide();
        $("#comptyp").hide();
        $("#mon_salar").hide();
        $("#comp").hide();
        $("#buis").hide();
        $("#ann_turn").show();
          $("#profess").on('change',function(){
              if($(this).val() == "Other"){
                $("#profess_other").show();
            }else{
                $("#profess_other").hide();
            }
          });

      }
      else if($(this).val() == "Self Employed Businessman"){
          
        $("#comp").show();
        $("#buis").show();
        $("#ann_turn").show();
        $("#inds").hide();
        $("#prof").hide();
        $("#comptyp").hide();
        $("#mon_salar").hide();
        $("#comp_type").on('change',function(){
           if($(this).val() == "Other"){
              $("#comp_type_other").show();
           }else{
              $("#comp_type_other").hide();
           }
        });
        $("#buis_nat").on('change',function(){
           if($(this).val() == "Other"){
             $("#buis_nat_other").show();
           }else{
            $("#buis_nat_other").hide();
           }
        });
      }
      else if($(this).val() == "Salaried"){
        $("#comptyp").show();
        $("#mon_salar").show();
        $("#inds").show();
        $("#prof").hide();
        $("#comp").hide();
        $("#buis").hide();
        $("#ann_turn").hide();
         $("#comp_salaried").on('change',function(){
            if($(this).val() == "Other"){
                $("#comp_salaried_other").show();
            }else{
                $("#comp_salaried_other").hide();
            }
         });
         $("#ind_type").on('change',function(){
            if($(this).val() == "Other"){
                $("#ind_types").show();
            }else{
              $("#ind_types").hide();  
            }
         });
      }else{
        $("#prof").hide();
        $("#profess_other").hide();
        $("#comp").hide();
        $("#comp_type_other").hide();
        $("#buis").hide();
        $("#buis_nat_other").hide();
        $("#comptyp").hide();
        $("#comp_salaried_other").hide();
        $("#inds").hide();
        $("#ind_types").hide();
        $("#mon_salar").hide();
        $("#ann_turn").hide();
      }
    });
    $('.type_data').on('change',function(){
       var pl = ["Income","RTR"];
       var bl = ["Income","GST","Banking","Turn Over","RTR","Rental","Liquid Income"];
       var hl = ["Income","GST","Banking","Turn Over","RTR","Low LTV","Rental","Liquid Income"];
       var hq = ["Income","GST","Banking","Turn Over","RTR","Low LTV","Rental","LRD","Liquid Income"];
       var wc = ["Income","GST","Banking","Turn Over","RTR","Rental","Liquid Income"];
       var ucl = ["Income","GST","Banking","Turn Over","RTR","Liquid Income"];
       var ncl = ["Income","GST","Banking","Turn Over","RTR","Liquid Income"];
       var cc = ["Income"];
       
       if($(this).val() == "Personal Loan"){
        for (var i=0;i<pl.length;i++){
          $('<option/>').val(pl[i]).html(pl[i]).appendTo('.prog');
        }
       }else if($(this).val() == "Business Loan"){
          for (var i=0;i<bl.length;i++){
            $('<option/>').val(bl[i]).html(bl[i]).appendTo('.prog');
          }
       }else if($(this).val() == "Home Loan- Construction"){
           for (var i=0;i<hl.length;i++){
            $('<option/>').val(hl[i]).html(hl[i]).appendTo('.prog');
           }
       }else if($(this).val() == "Home Equity - Residential" || $(this).val() == "Home Equity- Commercial"){
           for (var i=0;i<hq.length;i++){
             $('<option/>').val(hq[i]).html(hq[i]).appendTo('.prog');
           }
       }else if($(this).val() == "Working Capital"){
          for (var i=0;i<wc.length;i++){
             $('<option/>').val(wc[i]).html(wc[i]).appendTo('.prog');
           }
       }else if($(this).val() == "Used Car Loan"){
           for (var i=0;i<ucl.length;i++){
             $('<option/>').val(ucl[i]).html(ucl[i]).appendTo('.prog');
           }
       }else if($(this).val() == "New Car Loan"){
           for (var i=0;i<ncl.length;i++){
             $('<option/>').val(ncl[i]).html(ncl[i]).appendTo('.prog');
           }
       }else if($(this).val() == "Credit Card" || $(this).val() == "Health Insurance" || $(this).val() == "Life Insurance" || $(this).val() == "General Insurance"){
           for (var i=0;i<cc.length;i++){
             $('<option/>').val(cc[i]).html(cc[i]).appendTo('.prog');
           }
       }else{
          $('.prog').empty();
          var option = $("<option />");
          option.attr("value", '0').text('Select Program');
          $('.prog').append(option);
       }
    });
    
});

    //Add Banker Validation
    $('#addEditBankform').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "full_name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "short_name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
           
           
            
            
            "type":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            }
        }
    });

    $('#addEditBankerform').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        fields:{
            "banker_name":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Banker Name must be a string only.'
                    }

                }

            },

            "email":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
             "phone_number":{
                validators:{
                   notEmpty:{
                        message: 'This field is required'
                    },   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Phone Number must be 10 digits only'
                    },
                }
            },
            "state":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    }

                }

            },
            "city":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'City must be a string only.'
                    }

                }

            },
            "district":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    }

                }

            }
        }
    });

    //Get Refer Status while Allocation
    $(document).on('change','.getReferStatus',function(){
        $(".loadingDiv").show();
        var referstatus = $(this).val();
        $.ajax({
            url :'/s/admin/append-allocation-employees?refer='+referstatus,
            type : 'get',
            success:function(resp){
                $('#AppendEmpResults').html(resp);
                $(".loadingDiv").hide();
            },
            error:function(){} 
        })
    });

    //GGet Leadid
    $(document).on('change','.getleadid',function(){
        $(".loadingDiv").show();
        var leadid = $(this).val();
        if(leadid !=""){
            var companyname = $(this).find(':selected').data('companyname');
            var lead_id = $(this).find(':selected').data('leadid');
            var leadsource = $(this).find(':selected').data('source');
            $.ajax({
                url :'/s/admin/append-reminder-details',
                type : 'post',
                data: {leadid :leadid,companyname:companyname,lead_id:lead_id,source:leadsource},
                dataType : 'json',
                success:function(resp){
                    $("#AppendLeadLink").html(resp.leadlink);
                    $("#AppendEmployees").html(resp.appendemployees);
                    $("#includeMeinCC").html(resp.includeMeinCc);
                    $(".loadingDiv").hide();
                },
                error:function(){
                    alert('Error');
                }
            })
        }else{
            $("#AppendLeadLink").html('');
            $("#AppendEmployees").html('');
            $("#includeMeinCC").html('');
            $(".loadingDiv").hide();
        }
    }); 
   
   
   
   $('#addEditClient').formValidation({
       
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
		fields:{
		"customer_name":{
                validators:{
                    notEmpty:{
                        message: 'Customer Name is required.'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Customer Name must be a string only.'
                    }

                }

            },
            "mother_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Mother Name must be a string only.'
                    }

                }

            },
            "father_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Father Name must be a string only.'
                    }

                }

            },
            "spouse_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Spouse Name must be  string only.'
                    }

                }

            },
            "company_identifications":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Company Identifications must be  string only.'
                    }

                }

            },
            "email_ofc":{
                validators:{
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
            "coapp_mail":{
                validators:{
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
            "email_personal":{
                validators:{
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
            "email":{
                validators:{
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
			"adhar_no":{
                validators:{
                   
                    stringLength: {
                        min:12,
                        max: 12,
                        message: 'Aadhaar Number must be 12 digits only'
                    },
                    regexp: {
                        regexp: /^[2-9]{1}[0-9]{11}$/,
                        message: 'Aadhaar number shoulnot start with 0 or 1.'
                    },
                    
                }
            },
            "phone_number":{
                validators:{   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Phone Number must be 10 digits only'
                    },
                }
            },
            "coapp_mob":{
                validators:{   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Co-Applicant Mobile Number must be 10 digits only'
                    },
                }
            },
            "mobile":{
                validators:{   
                    notEmpty:{
                        message: 'Mobile Number is required.'
                    },
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Mobile Number must be 10 digits only'
                    },
                }
            },
            "alt_mobile":{
                validators:{   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Alternate Mobile Number must be 10 digits only'
                    },
                }
            },
            "current_city_years":{
                validators:{
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'This field must be only in digits.'
                    }
                }
            },
            "current_address_years":{
                validators:{
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'This field must be only in digits.'
                    }
                }
            },
            "permanent_mobile":{
                validators:{   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Permanent Mobile Number must be 10 digits only'
                    },
                }

            },
             "tot_work_experience":{
                validators:{
                    regexp: {
                        regexp: /^[1-9]\d*(\.\d+)?$/,
                        message: 'This field can be in decimal also.'
                    }
                }
            },
            "present_company_exp":{
                validators:{
                    regexp: {
                        regexp: /^[1-9]\d*(\.\d+)?$/,
                        message: 'This field can be in decimal also.'
                    }
                }
            },
            "institute_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Institute Name must be admin string only.'
                    }
                }
            },
            "year_of_passing":{
                validators:{
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'This field must be only in digits.'
                    }
                }
            },
            "name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Applicant Name must be admin string only.'
                    }
                }
            },
            
            "state":{
                validators:{
                    notEmpty:{
                        message: 'State is required.'
                    }
                }
            },
            "city":{
                validators:{
                    notEmpty:{
                        message: 'City is required.'
                    }
                }
            },
            
            "co_applicant_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Co-applicant Name must be admin string only.'
                    }
                }
            },
            
            
		}
		
		
		
	});
	
	
   
   
   
    $('#addEditClient_old').formValidation({
       
        framework: 'bootstrap',
        excluded: [':disabled'],
        message: 'This value is not valid',
        icon:{
            validating: 'glyphicon glyphicon-refresh'
        },
        err:{
            container: 'popover'
        },
        // rules: {
        //     "channel_partner": {
        //         validators:{
        //           required: {
        //             depends: () => $('#lead_origin').val() == 'channel partner'
        //           }
        //         }
        //     },
        //     "tel_name": {
        //       required: {
        //         depends: () => $('#lead_origin').val() == 'local'
        //       }
        //     },
        // },
        fields:{
            
           "customer_name":{
                validators:{
                    notEmpty:{
                        message: 'Customer Name is required.'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Customer Name must be a string only.'
                    }

                }

            },
            "mother_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Mother Name must be a string only.'
                    }

                }

            },
            "father_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Father Name must be a string only.'
                    }

                }

            },
            "spouse_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Spouse Name must be  string only.'
                    }

                }

            },
            "company_identifications":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Company Identifications must be  string only.'
                    }

                }

            },
            "email_ofc":{
                validators:{
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
            "coapp_mail":{
                validators:{
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
            "email_personal":{
                validators:{
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
            "email":{
                validators:{
                    emailAddress:{
                        message: 'This Email is not a valid email address'
                    }
                }
            },
            // "dob":{
            //     validators:{
            //         notEmpty:{
            //             message: 'This field is required'
            //         },
            //     }
            // },
            "lead_origin":{
                validators:{
                    notEmpty:{
                        message: 'This field is required'
                    },
                }
            },
            "channel_partner": {
                validators:{
                  notEmpty: {
                    depends: () => $('#lead_origin').val() == 'channel partner',
                    message: 'This field is required'
                  }
                }
            },
            "tel_name": {
              required: {
                depends: () => $('#lead_origin').val() == 'local',
                message: 'This field is required'
              }
            },
            "pan_status":{
                validators:{
                    notEmpty:{
                        message: 'Please Select PAN Status'
                    }
                }
            },

            "adhar_no":{
                validators:{
                   
                    stringLength: {
                        min:12,
                        max: 12,
                        message: 'Aadhaar Number must be 12 digits only'
                    },
                    regexp: {
                        regexp: /^[2-9]{1}[0-9]{11}$/,
                        message: 'Aadhaar number shoulnot start with 0 or 1.'
                    },
                    // remote:{
                    //     message: 'This Adhar Number already exists.',
                    //     url: '/s/admin/CheckClientPan?type=adhar',
                    //     type: 'POST',
                    //     delay: 2000     // Send Ajax request every 2 seconds
                    // }
                }
            },
            "phone_number":{
                validators:{   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Phone Number must be 10 digits only'
                    },
                }
            },
            "coapp_mob":{
                validators:{   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Co-Applicant Mobile Number must be 10 digits only'
                    },
                }
            },
            "mobile":{
                validators:{   
                    notEmpty:{
                        message: 'Mobile Number is required.'
                    },
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Mobile Number must be 10 digits only'
                    },
                }
            },
            "alt_mobile":{
                validators:{   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Alternate Mobile Number must be 10 digits only'
                    },
                }
            },
            "current_city_years":{
                validators:{
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'This field must be only in digits.'
                    }
                }
            },
            "current_address_years":{
                validators:{
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'This field must be only in digits.'
                    }
                }
            },
            "permanent_mobile":{
                validators:{   
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Permanent Mobile Number must be 10 digits only'
                    },
                }

            },
            // "company_name":{
            //     validators:{
            //         notEmpty:{
            //             message: 'Company Name is required.'
            //         },
            //         regexp: {
            //             regexp: /^[a-zA-Z\s]+$/,
            //             message: 'Company Name must be admin string only.'
            //         }
            //     }
            // },
            "tot_work_experience":{
                validators:{
                    regexp: {
                        regexp: /^[1-9]\d*(\.\d+)?$/,
                        message: 'This field can be in decimal also.'
                    }
                }
            },
            "present_company_exp":{
                validators:{
                    regexp: {
                        regexp: /^[1-9]\d*(\.\d+)?$/,
                        message: 'This field can be in decimal also.'
                    }
                }
            },
            "institute_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Institute Name must be admin string only.'
                    }
                }
            },
            "year_of_passing":{
                validators:{
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'This field must be only in digits.'
                    }
                }
            },
            "name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Applicant Name must be admin string only.'
                    }
                }
            },
            // "permenant_address":{
            //     validators:{
            //         notEmpty:{
            //             message: 'Permanent Address is required.'
            //         },
            //         regexp: {
            //             regexp: /^[a-zA-Z\s]+$/,
            //             message: 'Current Residential Address must be admin string only.'
            //         }
            //     }
            // },
            "state":{
                validators:{
                    notEmpty:{
                        message: 'State is required.'
                    }
                }
            },
            "city":{
                validators:{
                    notEmpty:{
                        message: 'City is required.'
                    }
                }
            },
            // "ofc_pincode":{
            //     validators:{   
            //         notEmpty:{
            //             message: 'Pin Code is required.'
            //         },
            //         stringLength: {
            //             min:6,
            //             max: 10,
            //             message: 'Pin Code must be digits only'
            //         },
            //     }
            // },
            "co_applicant_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Co-applicant Name must be admin string only.'
                    }
                }
            },
            "reference_name":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Reference Name must be admin string only.'
                    }
                }
            },
            "proper_address":{
                validators:{
                    regexp: {
                        regexp: /^[a-zA-Z\s]+$/,
                        message: 'Address must be admin string only.'
                    }
                }
            }
            
        }
    });
   
   $(document).ready(function(){
     $("#chan_part").hide();
     $("#telc_list").hide();
     $("#origin_data").on('change',function(){
        var x = $(this).val();

        if(x == "channel partner"){
           $("#chan_part").show();
           $("#telc_list").hide();
        }else if(x == "local"){
           
            $("#telc_list").show();
            $("#chan_part").hide();
        }else{
             $("#chan_part").hide();
             $("#telc_list").hide();
        }

     });
     
     $("#sp_name").hide();
     $("#sp_dob").hide();
     $("#mar_status").on('change',function(){
       var y = $(this).val();
       if(y == "Married"){
        $("#sp_name").show();
        $("#sp_dob").show();
       }else{
        $("#sp_name").hide();
        $("#sp_dob").hide();
       }
     });


   });
    //AJAX ON Pan Status 
    $(document).on('change','#getPanStatus',function(){
        $(".loadingDiv").show();
        var panstatus = $(this).val();

        if(panstatus=="yes"){
            $("#AppendPan").html('<label class="col-md-3 control-label">Pan Individual: </label><div class="col-md-5"><input type="text" id="pan_value" name="pan" autocomplete="off" placeholder="PAN" class="form-control" style="color:gray" /><span class="pan_err" style="display: none;">This PAN Number already exists!!</span></div>');
            $('#pan_value').on('keyup', function(){

            var pan = $(this).val();
             
         // $.ajax({
         //    type : 'post',
         //    url : '/s/admin/CheckClientPan',
         //    data : {pan: pan},
         //    success:function(resp){
         //       if(resp.success == "false"){
         //         $('.pan_err').css({
         //                 'padding-left' : '15px',
         //                 'color': 'red',
         //                 'display' : 'block' 
         //          }).show();
                 
         //       }else{
         //         $('.pan_err').css({
         //                 'padding-left' : '15px',
         //                 'color': 'red',
         //                 'display' : 'block' 
         //          }).hide();
         //       }
         //    },
         //    error:function(){}
         // });
            
        });
             $(".loadingDiv").hide();
            $option = "pan";
            $('#addEditClient').formValidation('addField', $option, {
                validators:{   
                    notEmpty:{
                        message: 'PAN is required'
                    },
                    stringLength: {
                        min:10,
                        max: 10,
                        message: 'Pan Number must be 10 digits only'
                    },
                    regexp: {
                        regexp: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/,
                        message: 'For pan number first 5 characters should be uppercase alphabets next 4 characters should be only digits and last one should be an uppercase alphabet.'
                    },
                    // remote:{
                    //     message: 'This Pan already exists.',
                    //     url: '/s/admin/CheckClientPan',
                    //     type: 'POST',
                    //     delay: 2000     // Send Ajax request every 2 seconds
                    // }
                }
            });
            $("#AppendPan").addClass('in');
        }else{
            $find = $('.form-group');
            if($("#AppendPan").length > 0){
                $('#addEditClient')
                .formValidation('removeField', $find.find('[name="pan"]'));
                $("#AppendPan").remove();
                var $target = $('#AppenderPan');
                $target.after('<div class="form-group collapse" id="AppendPan"></div>');
            }
            $(".loadingDiv").hide();
        }
        
        });

    $(document).ready(function(){
        // $('#ad_value').on('keyup', function(){
        //     var adhar_no = $(this).val();
        //  $.ajax({
        //     type : 'post',
        //     url : '/s/admin/CheckClientPan?type=adhar',
        //     data : {adhar_no: adhar_no},
        //     success:function(resp){
        //        if(resp.success == "false"){
        //          $('.adh_err').css({
        //                  'padding-left' : '15px',
        //                  'color': 'red',
        //                  'display' : 'block' 
        //           }).show();
                 
        //        }else{
        //          $('.adh_err').css({
        //                  'padding-left' : '15px',
        //                  'color': 'red',
        //                  'display' : 'block' 
        //           }).hide();
        //        }
        //     },
        //     error:function(){}
        //  });
            
        // }); 

        
    });
    
});
function ConfirmDelete() {
    if(confirm('Are you sure?')){
        e.preventDefault();
        return true;
    }
    return false;
}