$(document).ready(function(){
    //ACTION FOR TOP BUTTON
    $('.btn-topo').click(function() {
        $('body,html').animate({scrollTop:0},800);
    });
    //PRODUCTS FILTER
    $('.products-filter').change(function() {
        window.location = $(this).val();
    });
    //FORGOT PASSWORD
    $('#forgot-password').click(function() {
        $('.login').hide();
        $('.forgot-password').show();

    });
    //RETURN LOGIN
    $('#return-login').click(function() {
        $('.login').show();
        $('.forgot-password').hide();

    });
    //ACTION FOR MENU
    $(document).click(function(event) {
        if(!$(event.target).closest('.navigation').length) {
            $('.navigation .arrow').hide();
            $('.navigation').hide();
            $('.navigation nav').hide();
            $('.navigation .login').hide();
            $('.navigation .forgot-password').hide();
            $('.navigation .user-data').hide();
            $('.navigation .search').hide();

            if($(event.target).closest('.btn-nav').length) {
                $('.navigation').fadeIn();
                $('.navigation nav').fadeIn();
                $('.navigation .arrow').show();
            }
            if($(event.target).closest('.btn-login').length) {
                $('.navigation').fadeIn();
                $('.navigation .login').fadeIn();
            }
            if($(event.target).closest('.btn-user-data').length) {
                $('.navigation').fadeIn();
                $('.navigation .user-data').fadeIn();
            }
            if($(event.target).closest('.btn-search').length) {
                $('.navigation').fadeIn();
                $('.navigation .search').fadeIn();
            }
        }
    });
    //PRINT AND TEXT SIZE
    $('.print').click(function() {
        $(".text-page").print();
        //window.print();
        return false;
    });
    var originalFontSize = $('.text').css('font-size')
    $(".text-size").click(function(){
        var currentFontSize = $('.text').css('font-size');
        if(currentFontSize != '14px') {
            $('.text').css('font-size', originalFontSize);
            return false;
        }
        var currentFontSizeNum = parseFloat(currentFontSize, 10);
        var newFontSize = currentFontSizeNum*1.2;
        $('.text').css('font-size', newFontSize);
        return false;
    });

    //VALIDATE LOGIN FORM
    $('#form-login').validate({
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
            }
        }
    });
    //VALIDATE RECOVERY PASSWORD FORM
    $('#form-recovery-password').validate({
        ignore: [],
        rules: {
            'email': {
                required: true,
                email: true
            }
        },
        messages: {
            'email': {
                required: 'Informe seu e-mail',
                email: 'Informe um e-mail válido'
            }
        }
    });
    //FILTER CITIES PER STATE
    $("select[name=state]").change(function(){
        var val = $(this).val();
        $.ajax({
            url: '/cities',
            type: 'POST',
            dataType: 'json',
            data: {'state': val, '_token': $('input[name=_token]').val()},
            beforeSend: function () {
                $("select[name=city] option:selected").text('Carregando cidades...');
                $("select[name=city]").attr('disabled', 'disabled');
            },
            success: function(data){
                $("select[name=city]").removeAttr('disabled');
                var cities = '<option value="">Escolha a cidade...</option>';
                $.each(data, function (key, val) {
                    cities += '<option value="' + val.name + '">' + val.name + '</option>';
                });
                $("select[name=city]").html(cities);
            }
        });
    });
    //PEOPLE TYPE - FARMACOVIGILANCE
    $('input[type=radio][name=peopleType]').click(function () {
        var result = $(this).val();
        if(result == "Física"){
            $('.fisical-people').removeClass('hidden');
            $('.juridical-people').addClass('hidden');
        }else{
            $('.fisical-people').addClass('hidden');
            $('.juridical-people').removeClass('hidden');
        }
    });

    //PROFILE - EDIT MY DATA
    $(".form-profile label p").click(function(e){
        var element = $(this).attr('id');
        $('.form-profile p#'+element).hide();
        if(element != 'babyGender' && element != 'gender' && element != 'state'){
            $('.form-profile input[name='+element+']').show();
            $('.form-profile input[name='+element+']').focus();
        }else{
            $('.form-profile select[name='+element+']').show();
            $('.form-profile select[name='+element+']').focus();
        }
    });
    $('.form-profile label input').keypress(function(e){
        var id = $(this).attr('id');
        var inputName = $(this).attr('name');
        var inputRequired = $(this).attr('required');
        var inputValue = $(this).val();
        /* * making sure if the event is Keycode (for IE and other browsers) * if not take Which event (Firefox) */
        var key = (e.keyCode?e.keyCode:e.which);
        /* making sure if the key press has been pressed the "ENTER" */
        if(key == 13){
            if(inputRequired == 'required' && inputValue == '') {
                alert('Preenchimento Obrigatório!');
                $(this).focus();
            }else if($('label.error[for='+id+']').length > 0 && (!$('label.error[for='+id+']').attr('style') || $('label.error[for='+id+']').attr('style') == "display: block;")){
                alert('Existem erros pendentes!');
                $(this).focus();
            }else{
                var data = {
                    '_token' : $('input[type=hidden][name=_token]').val(),
                    'id' : $('input[type=hidden][name=userId]').val(),
                    'inputName' : inputName,
                    'inputValue' : inputValue
                };
                $.ajax({
                    type : "PUT",
                    url: "/profile",
                    data: data,
                    dataType: "json",
                    success: function(d){
                        if(d['success'] == 0){
                            alert(d['message']);
                        }else {
                            $('input[name='+inputName+']').hide();
                            r = d['newInputValue'];
                            if (r == '') {
                                r = '- - -';
                            }
                            $('.form-profile p#' + inputName).html(r);
                            $('.form-profile p#' + inputName).show();
                        }
                    }
                });
            }
        }else{
            return true;
        }
    });
    $('.form-profile label select').change(function(e){
        var id = $(this).attr('id');
        var inputName = $(this).attr('name');
        var inputRequired = $(this).attr('required');
        var inputValue = $(this).val();
        if(inputRequired == 'required' && inputValue == ''){
            alert('Preenchimento Obrigatório!');
            $(this).focus();
        }else {
            var data = {
                '_token' : $('input[type=hidden][name=_token]').val(),
                'id': $('input[type=hidden][name=userId]').val(),
                'inputName': inputName,
                'inputValue': inputValue
            };
            $.ajax({
                type: "PUT",
                url: "/profile",
                data: data,
                dataType: "json",
                success: function (d) {
                    if(d['success'] == 0){
                        alert(d['message']);
                    }else {
                        $('select[name='+inputName+']').hide();
                        r = d['newInputValue'];
                        $('.form-profile p#' + inputName).html(r);
                        $('.form-profile p#' + inputName).show();
                    }
                }
            });
        }
    });
    $('.form-profile').validate({
        ignore: [],
        rules: {
            'cpf': {
                required: true,
                cpf: true
            },
            'email': {
                required: true,
                email: true
            }
        },
        messages: {
            'cpf': {
                required: 'Informe o CPF'
            },
            'email': {
                required: 'Informe seu e-mail',
                email: 'Informe um e-mail válido'
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