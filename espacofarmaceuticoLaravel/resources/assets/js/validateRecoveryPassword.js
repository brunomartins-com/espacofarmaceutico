$(function(){
    $('.form-recovery').validate({
        ignore: [],
        rules: {
            'email': {
                required: true,
                email: true
            },
            'password': {
                required: true,
                minlength: 6,
                maxlength: 12
            },
            'password_confirmation': {
                required:true,
                minlength: 6,
                maxlength: 12,
                equalTo:"#password"
            }
        },
        messages: {
            'email': {
                required: 'Informe seu e-mail',
                email: 'Informe um e-mail válido'
            },
            'password': {
                required	: "Informe a senha",
                minlength	: "A senha deve conter de 6 a 12 caracteres",
                maxlength	: "A senha deve conter de 6 a 12 caracteres"
            },
            'password_confirmation': {
                required	: "Confirme a senha",
                minlength	: "A senha deve conter de 6 a 12 caracteres",
                maxlength	: "A senha deve conter de 6 a 12 caracteres",
                equalTo		: "As senhas não conferem"
            }
        }
    });
    $('input[type=text][name=phone]').keyup(function(){
       $('label.error[for=mobile]').html('');
       $('label.error[for=mobile]').hide();
    });
    $('input[type=text][name=mobile]').keyup(function(){
        $('label.error[for=phone]').html('');
        $('label.error[for=phone]').hide();
    });
});