
$('.login_buutton').click(function () {


    let email = $('.login_email').val();
    // let token = $('.login_email').val();
    let password  = $('.login_password').val();
    let remember = $('.checkbox_login').prop('checked')?1:0;


    let data = {
        '_token':$('meta[name="csrf-token"]').attr('content'),
        'email': email,
        'password': password,
        'remember': remember,

    }
    // var base_url = $('#url').val();
    // $.ajax({
    //     url: "/login",
    //     type: "POST",
    //     cache: false,
    //     contentType: false,
    //     processData: false,
    //     data: formData,
    //     success: function (response) {
    //
    //         console.log(response)
    //
    //     },
    //     error: function (response) {
    //         console.log(response)
    //     }
    // });


    $.post('/login', data)

        .done( function(msg) {
            window.location.pathname = '/';
            console.log(msg);
        })

        .fail( function(xhr, textStatus, errorThrown) {
            console.log(xhr)
            $('.invalid_error').css('display','flex')
            $('.invalid_error span').text(xhr.responseJSON.message)
            Object.keys(xhr.responseJSON.errors).forEach((e)=>{
                if(e == 'email'){
                    $('.login_email').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    $('.login_password').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    $('.invalid_error span').text(xhr.responseJSON.errors[e][0])

                }
                if(e == 'password'){
                    // $('.login_email').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    $('.login_password').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    $('.invalid_error span').text(xhr.responseJSON.errors[e][0])
                }
            })
        });
});

$(".login_email").keypress(function (e) {
    console.log('textareaclass')
    if(e.which === 13 && !e.shiftKey) {
        $('.login_buutton').click()
    }
});
$(".login_password").keypress(function (e) {
    console.log('textareaclass')
    if(e.which === 13 && !e.shiftKey) {
        $('.login_buutton').click()
    }
});

$(".register_email").keypress(function (e) {
    console.log('textareaclass')
    if(e.which === 13 && !e.shiftKey) {
        $('.register_button').click()
    }
});
$(".register_Phone").keypress(function (e) {
    console.log('textareaclass')
    if(e.which === 13 && !e.shiftKey) {
        $('.register_button').click()
    }
});
$(".register_Password").keypress(function (e) {
    console.log('textareaclass')
    if(e.which === 13 && !e.shiftKey) {
        $('.register_button').click()
    }
});
$(".register_Password_Confimation").keypress(function (e) {
    console.log('textareaclass')
    if(e.which === 13 && !e.shiftKey) {
        $('.register_button').click()
    }
});
$(".Reset_email").keypress(function (e) {
    console.log('textareaclass')
    if(e.which === 13 && !e.shiftKey) {
        $('.forget_password_button').click()
    }
});

$('.register_button').click(function () {
    var data = {
        "_token": $('meta[name="csrf-token"]').attr('content'),
        'email' : $('.register_email').val(),
        // 'phone' : $('.register_Phone').val(),
        'password' : $('.register_Password').val(),
        'password_confirmation' : $('.register_Password_Confimation').val(),
        'remember' : $('.register_checkbox').prop('checked')
    };

    console.log(data);

    $.post('/register', data)

        .done( function(msg) {
            window.location.pathname = '/';
            console.log(msg);
        })

        .fail( function(xhr, textStatus, errorThrown) {
            Object.keys(xhr.responseJSON.errors).forEach((e)=>{
                $('.invalid_error').css('display','flex')
                $('.invalid_error span').text(xhr.responseJSON.message)
                if(e == 'email'){
                    $('.register_email').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    $('.invalid_error span').text(xhr.responseJSON.errors[e][0])
                }
                if(e == 'password'){
                    $('.register_Password').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    $('.register_Password_Confimation').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    if('validation.min.string' == xhr.responseJSON.errors[e][0]){
                        $('.invalid_error span').text('Password Must be at least 8 characters')
                    }else {
                        $('.invalid_error span').text(xhr.responseJSON.errors[e][0])
                    }

                }
                if(e == 'remember'){
                    $('.remember_me_check').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                // if(e == 'phone'){
                //     $('.register_Phone').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                // }
            })

            // console.log(xhr.responseJSON.errors);
        });
});
