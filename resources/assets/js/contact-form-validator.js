$(document).ready(function () {

    $('#contact-name').keyup(function () {
        $('#contact-name').removeClass('input-with-error');
    });
    $('#contact-email').keyup(function () {
        $('#contact-email').removeClass('input-with-error');
    });
    $('#contact-subject').keyup(function () {
        $('#contact-subject').removeClass('input-with-error');
    });
    $('#contact-message').keyup(function () {
        $('#contact-message').removeClass('input-with-error');
    });

    $('#contact-form-btn').click(function () {
        var inputs = {};
        inputs.name = $('#contact-name').val();
        inputs.email = $('#contact-email').val();
        inputs.subject = $('#contact-subject').val();
        inputs.message = $('#contact-message').val();
        var error = false;
        var errors = {};
        if(!inputs.name) {
            errors.name = true;
            error = true;
        }
        if(!inputs.subject) {
            errors.subject = true;
            error = true;
        }
        if(!inputs.message) {
            errors.message = true;
            error = true;
        }
        if(!inputs.email) {
            errors.email = true;
            error = true;
        }
        var regexEmail = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
        if(!regexEmail.test(inputs.email)){
            errors.email = true;
            error = true;
        }
        for(var e in errors) {
            $('#contact-' + e).addClass('input-with-error');
        }
        if(error == true) {
            return false;
        }
    });
});