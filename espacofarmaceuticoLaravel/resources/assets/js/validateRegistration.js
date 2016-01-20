$(function(){
    $('.form-registration').validate({
        ignore: [],
        rules: {
            'babyName': {
                required: true
            },
            'babyBirthdate': {
                required: true
            },
            'babyGender': {
                required: true
            },
            'birthCertificateFile': {
                required: true,
                accept: "image/*"
            },
            'name': {
                required: true
            },
            'rg': {
                required: true
            },
            'cpf': {
                required: true,
                cpf: true
            },
            'address': {
                required: true
            },
            'gender': {
                required: true
            },
            'number': {
                required: true
            },
            'district': {
                required: true
            },
            'state': {
                required: true
            },
            'city': {
                required: true
            },
            'phone': {
                required: function(element){
                    if($("#mobile").val() == ''){
                        return true;
                    }else{
                        return false;
                    }
                }
            },
            'mobile': {
                required: function(element){
                    if($("#phone").val() == ''){
                        return true;
                    }else{
                        return false;
                    }
                }
            },
            'email': {
                required: true,
                email: true
            },
            'terms': {
                required:true
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
            'babyName': {
                required: 'Informe o nome do bebê'
            },
            'babyBirthdate': {
                required: 'Informe a data de nascimento do bebê'
            },
            'babyGender': {
                required: 'Marque o sexo do bebê'
            },
            'name': {
                required: 'Informe o nome do responsável'
            },
            'rg': {
                required: 'Informe o RG'
            },
            'cpf': {
                required: 'Informe o CPF'
            },
            'address': {
                required: 'Informe o endereço'
            },
            'gender': {
                required: 'Marque o sexo'
            },
            'number': {
                required: 'Informe o número'
            },
            'district': {
                required: 'Informe o bairro'
            },
            'state': {
                required: 'Escolha o Estado'
            },
            'city': {
                required: 'Escolha a cidade'
            },
            'phone': {
                required: 'Informe um número de contato'
            },
            'mobile': {
                required: 'Informe um número de contato'
            },
            'email': {
                required: 'Informe seu e-mail',
                email: 'Informe um e-mail válido'
            },
            'terms': {
                required: 'Você precisa estar de acordo'
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