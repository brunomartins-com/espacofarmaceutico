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
    //OPEN TEUTO 360º
    $('#teuto-360').click(function() {
        window.open('http://www.espacofarmaceutico.com.br/teuto-360', '', 'resizable=0,Menubar=no,toolbar=no,scrollbars=0,status=0,top=0,left=0,screenX=' + window.screenLeft + ',screenY=' + window.screenTop + ',width=820,height=620');
    });
    //MENU SEARCH
    $('.search-menu a').click(function() {
        $('.search-results').addClass('hidden');
        var idDivShow = $(this).data('type');
        $('#'+idDivShow).removeClass('hidden');
        $('.search-menu a').removeClass('active');
        $('.search-menu a[data-type='+idDivShow+']').addClass('active');
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
    var originalFontSize = $('.text').css('font-size');
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
});