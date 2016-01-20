$(function(){
    $('.form-contact').validate({
        ignore: [],
        rules: {
            'name': {
                required: true
            },
            'email': {
                required: true,
                email: true
            },
            'state': {
                required: true
            },
            'city': {
                required: true
            },
            'message': {
                required: true
            }
        },
        messages: {
            'name': {
                required: 'Informe seu nome'
            },
            'email': {
                required: 'Informe seu e-mail',
                email: 'Informe um e-mail válido'
            },
            'state': {
                required: 'Informe seu Estado'
            },
            'city': {
                required: 'Informe sua cidade'
            },
            'message': {
                required: 'Digite sua mensagem'
            }
        }
    });
});