<?php
Route::group(['middleware' => 'web'], function() {
    ## HOME
    Route::get('/', 'Website\HomeController@index');

    ## O TEUTO
    Route::get('o-teuto', 'Website\TheTeutoController@index');

    ## UNIVERSIDADE TEUTO
    Route::get('universidade-teuto', 'Website\TeutoUniversityController@index');

    ## BLOG
    Route::get('blog', 'Website\BlogController@index');
    Route::get('blog/busca', 'Website\BlogController@index');
    Route::get('blog/{year}/{month}/{day}/{slug}', 'Website\BlogController@read');

    ## NOTICIAS E RELEASES
    Route::get('noticias-e-releases', 'Website\NewsAndReleasesController@index');
    Route::get('noticias-e-releases/busca', 'Website\NewsAndReleasesController@index');
    Route::get('noticias-e-releases/{year}/{month}/{day}/{slug}', 'Website\NewsAndReleasesController@read');

    ## MEDICAMENTOS GENERICOS
    Route::group(['prefix' => 'medicamentos-genericos'], function() {
        ## PERGUNTAS FREQUENTES
        Route::get('/', 'Website\GenericsMedicationsController@commonQuestions');
        Route::get('perguntas-frequentes', 'Website\GenericsMedicationsController@commonQuestions');
        ## CONCEITO
        Route::get('conceito', 'Website\GenericsMedicationsController@concept');
        ## REDUÇÃO DOS GASTOS COM A SAUDE
        Route::get('reducao-dos-gastos-com-a-saude', 'Website\GenericsMedicationsController@reducingSpendingOnHealth');
        ## LEGISLACAO
        Route::get('legislacao', 'Website\GenericsMedicationsController@legislation');
        ## CONFIABILIDADE E QUALIDADE
        Route::get('confiabilidade-e-qualidade', 'Website\GenericsMedicationsController@reliabilityAndQuality');
    });

    ## VISITE BEM
    Route::group(['prefix' => 'visite-bem', 'middleware' => 'web'], function() {
        ## SOBRE O VISITE BEM
        Route::get('/', 'Website\VisitWellController@index');
        ## FOTOS
        Route::get('fotos', 'Website\VisitWellController@photos');
        Route::post('fotos', 'Website\VisitWellController@filterPhotos');
        Route::get('fotos/{year}/{month}/{day}/{slug}', 'Website\VisitWellController@photos');
        ## AGENDE SUA VISITA
        Route::get('agende-sua-visita', 'Website\VisitWellController@getScheduleYourVisit');
        Route::post('agende-sua-visita', 'Website\VisitWellController@postScheduleYourVisit');
    });

    ## MATERIAL DE APOIO
    Route::get('material-de-apoio', 'Website\SupportMaterialController@apps');
    Route::get('material-de-apoio/aplicativos', 'Website\SupportMaterialController@apps');
    Route::get('material-de-apoio/calculadora-imc', 'Website\SupportMaterialController@imcCalculator');
    Route::post('material-de-apoio/calculadora-imc', 'Website\SupportMaterialController@imcCalculator');

    ## CONSELHOS REGIONAIS
    Route::get('conselhos-regionais', 'Website\RegionalCouncilsController@index');

    ## FARMACOVIGILANCIA
    Route::get('farmacovigilancia', 'Website\FarmacovigilanceController@index');
    Route::post('farmacovigilancia', 'Website\FarmacovigilanceController@send');

    ## TRABALHE CONOSCO
    Route::get('trabalhe-conosco', 'Website\WorkWithUsController@index');

    ## CADASTRE-SE
    Route::get('cadastre-se', 'Website\RegistrationController@index');
    Route::post('cadastre-se', 'Auth\AuthController@postRegister');
    Route::get('cadastre-se/confirmacao/{token?}', 'Website\RegistrationController@getConfirmation');
    //Route::post('cadastre-se', 'Website\RegistrationController@send');

    ## CONTATO
    Route::get('contato', 'Website\ContactController@index');
    Route::post('contato', 'Website\ContactController@send');

    ## CITIES
    Route::post('cities', 'Website\CitiesController@post');

    ## SEARCH
    Route::post('busca', 'Website\SearchController@post');

    ## RECOVERY PASSWORD
    Route::post('recuperar-senha', 'Auth\PasswordController@postEmail');
    Route::get('recuperar-senha/{token}', ['as' => 'passwordReset', 'uses' => 'Auth\PasswordController@getResetWebsite']);
    Route::post('nova-senha', ['as' => 'passwordReset', 'uses' => 'Auth\PasswordController@postResetWebsite']);

    ## LOGIN WEBSITE
    Route::get('login', function () {
        return redirect('/')->with('message', 'Página restrita a usuários cadastrados');
    });
    Route::post('login', 'Auth\AuthController@postLoginWebsite');

    Route::group(['middleware' => 'auth'], function () {

        ## PROFILE
        Route::get('meus-dados', 'Website\ProfileController@getData');
        Route::put('meus-dados', 'Website\ProfileController@putDataUpdate');

        Route::get('meu-endereco', 'Website\ProfileController@getAddress');
        Route::put('meu-endereco', 'Website\ProfileController@putAddressUpdate');

        ## LOGOUT WEBSITE
        Route::get('sair', 'Auth\AuthController@getLogout');

        ## PRODUTOS
        Route::get('produtos', 'Website\ProductsController@index');
        Route::get('produtos/categoria/{slug}', 'Website\ProductsController@index');
        Route::get('produtos/catalogos-digitais', 'Website\ProductsController@digitalCatalogs');
        Route::get('produtos/principio-ativo/{activePrincipleSlug}', 'Website\ProductsController@index');
        Route::post('produtos/busca', 'Website\ProductsController@search');

        ## EVENTOS
        Route::get('eventos', 'Website\EventsController@index');
        Route::get('eventos/{type}', 'Website\EventsController@index');

        ## SEU NEGOCIO MAIS LUCRATIVO
        Route::get('seu-negocio-mais-lucrativo', 'Website\YourBusinessMoreLucrativeController@index');

        ## INSTITUTO BULLA
        Route::get('instituto-bulla', 'Website\BullaInstituteController@index');

        ## VIDEOS 3D
        Route::get('videos-3d', 'Website\Movies3DController@index');
        Route::get('videos-3d/{year}/{month}/{day}/{slug}', 'Website\Movies3DController@watch');

    });

    // ADMIN AUTHENTICATION
    Route::get('admin', function () {
        return redirect(route('login'));
    });
    Route::get('admin/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
    Route::post('admin/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);

    // PASSWORD RESET LINK REQUEST
    Route::get('admin/senha/email', ['as' => 'passwordEmail', 'uses' => 'Auth\PasswordController@getEmail']);
    Route::post('admin/senha/email', ['as' => 'passwordEmail', 'uses' => 'Auth\PasswordController@postEmail']);
    // PASSWORD RESET
    Route::get('admin/recuperar-senha/{token}', ['as' => 'passwordReset', 'uses' => 'Auth\PasswordController@getReset']);
    Route::post('admin/senha/nova', ['as' => 'passwordReset', 'uses' => 'Auth\PasswordController@postReset']);


    ## ADMIN
    Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {

        ## HOME
        Route::get('home', ['as' => 'home', 'uses' => 'Admin\HomeController@home']);

        ## PARTICIPANTS
        Route::get('participantes', ['as' => 'participants', 'uses' => 'Admin\ParticipantsController@getIndex']);
        Route::get('participantes/visualizar/{userId?}', ['as' => 'participantsView', 'uses' => 'Admin\ParticipantsController@getView']);
        Route::put('participantes/status', ['as' => 'participantsStatus', 'uses' => 'Admin\ParticipantsController@putStatus']);
        Route::delete('participantes/excluir', ['as' => 'participantsDelete', 'uses' => 'Admin\ParticipantsController@delete']);

        ## BANNERS
        Route::get('banners', ['as' => 'banners', 'uses' => 'Admin\BannersController@getIndex']);
        Route::get('banners/adicionar', ['as' => 'bannersAdd', 'uses' => 'Admin\BannersController@getAdd']);
        Route::post('banners/adicionar', ['as' => 'bannersAdd', 'uses' => 'Admin\BannersController@postAdd']);
        Route::get('banners/editar/{bannersId?}', ['as' => 'bannersEdit', 'uses' => 'Admin\BannersController@getEdit']);
        Route::put('banners/editar', ['as' => 'bannersEditPut', 'uses' => 'Admin\BannersController@putEdit']);
        Route::delete('banners/excluir', ['as' => 'bannersDelete', 'uses' => 'Admin\BannersController@delete']);

        ## CALLS
        Route::get('chamadas', ['as' => 'calls', 'uses' => 'Admin\CallsController@getIndex']);
        Route::get('chamadas/adicionar', ['as' => 'callsAdd', 'uses' => 'Admin\CallsController@getAdd']);
        Route::post('chamadas/adicionar', ['as' => 'callsAdd', 'uses' => 'Admin\CallsController@postAdd']);
        Route::get('chamadas/editar/{callsId?}', ['as' => 'callsEdit', 'uses' => 'Admin\CallsController@getEdit']);
        Route::put('chamadas/editar', ['as' => 'callsEditPut', 'uses' => 'Admin\CallsController@putEdit']);
        Route::delete('chamadas/excluir', ['as' => 'callsDelete', 'uses' => 'Admin\CallsController@delete']);

        ## THE TEUTO
        Route::get('o-teuto', ['as' => 'theTeuto', 'uses' => 'Admin\TheTeutoController@getIndex']);
        Route::put('o-teuto/editar', ['as' => 'theTeutoPut', 'uses' => 'Admin\TheTeutoController@putUpdate']);

        ## REGULATION
        Route::get('regulamento', ['as' => 'regulation', 'uses' => 'Admin\RegulationController@getIndex']);
        Route::put('regulamento/editar', ['as' => 'regulationPut', 'uses' => 'Admin\RegulationController@putUpdate']);

        ## AWARDS
        Route::get('premios', ['as' => 'awards', 'uses' => 'Admin\AwardsController@getIndex']);
        Route::get('premios/editar/{awardsId?}', ['as' => 'awardsEdit', 'uses' => 'Admin\AwardsController@getEdit']);
        Route::put('premios/editar', ['as' => 'awardsEditPut', 'uses' => 'Admin\AwardsController@putEdit']);

        ## WINNERS 2014
        Route::get('ganhadores-2014', ['as' => 'winners2014', 'uses' => 'Admin\Winners2014Controller@getIndex']);
        Route::get('ganhadores-2014/editar/{winnersLastYearId?}', ['as' => 'winners2014Edit', 'uses' => 'Admin\Winners2014Controller@getEdit']);
        Route::put('ganhadores-2014/editar', ['as' => 'winners2014EditPut', 'uses' => 'Admin\Winners2014Controller@putEdit']);

        ## PRODUCTS
        Route::get('produtos', ['as' => 'products', 'uses' => 'Admin\ProductsController@getIndex']);
        Route::get('produtos/adicionar', ['as' => 'productsAdd', 'uses' => 'Admin\ProductsController@getAdd']);
        Route::post('produtos/adicionar', ['as' => 'productsAdd', 'uses' => 'Admin\ProductsController@postAdd']);
        Route::get('produtos/editar/{productsId?}', ['as' => 'productsEdit', 'uses' => 'Admin\ProductsController@getEdit']);
        Route::put('produtos/editar', ['as' => 'productsEditPut', 'uses' => 'Admin\ProductsController@putEdit']);
        Route::delete('produtos/excluir', ['as' => 'productsDelete', 'uses' => 'Admin\ProductsController@delete']);
        ## PRODUCTS CATEGORIES
        Route::get('produtos/categorias', ['as' => 'productsCategories', 'uses' => 'Admin\ProductsController@getCategories']);
        Route::get('produtos/categorias/adicionar', ['as' => 'productsCategoriesAdd', 'uses' => 'Admin\ProductsController@getCategoriesAdd']);
        Route::post('produtos/categorias/adicionar', ['as' => 'productsCategoriesAdd', 'uses' => 'Admin\ProductsController@postCategoriesAdd']);
        Route::get('produtos/categorias/editar/{productsCategoriesId}', ['as' => 'productsCategoriesEdit', 'uses' => 'Admin\ProductsController@getCategoriesEdit']);
        Route::put('produtos/categorias/editar', ['as' => 'productsCategoriesEditPut', 'uses' => 'Admin\ProductsController@putCategoriesEdit']);
        Route::get('produtos/categorias/ordenar', ['as' => 'productsCategoriesOrder', 'uses' => 'Admin\ProductsController@getCategoriesOrder']);
        Route::put('produtos/categorias/excluir', ['as' => 'productsCategoriesDelete', 'uses' => 'Admin\ProductsController@deleteCategories']);

        ## WINNERS
        Route::get('vencedores', ['as' => 'winners', 'uses' => 'Admin\WinnersController@getIndex']);
        Route::get('vencedores/adicionar', ['as' => 'winnersAdd', 'uses' => 'Admin\WinnersController@getAdd']);
        Route::post('vencedores/categoria', ['as' => 'winnersCategory', 'uses' => 'Admin\WinnersController@postCategory']);
        Route::put('vencedores/adicionar', ['as' => 'winnersAdd', 'uses' => 'Admin\WinnersController@putAdd']);
        Route::put('vencedores/excluir', ['as' => 'winnersDelete', 'uses' => 'Admin\WinnersController@putDelete']);

        ## NEWSLETTER
        Route::get('newsletter', ['as' => 'newsletter', 'uses' => 'Admin\NewsletterController@getIndex']);
        Route::get('newsletter/exportar', ['as' => 'newsletterExport', 'uses' => 'Admin\NewsletterController@getExport']);
        Route::delete('newsletter/excluir', ['as' => 'newsletterDelete', 'uses' => 'Admin\NewsletterController@delete']);

        ## PAGES
        Route::get('paginas', ['as' => 'pages', 'uses' => 'Admin\PagesController@getIndex']);
        Route::get('paginas/editar/{pagesAdminId?}', ['as' => 'pagesEdit', 'uses' => 'Admin\PagesController@getEdit']);
        Route::put('paginas/editar', ['as' => 'pagesEditPut', 'uses' => 'Admin\PagesController@putEdit']);

        ## WEBSITE SETTINGS
        Route::get('dados-do-site', ['as' => 'websiteSettings', 'uses' => 'Admin\WebsiteSettingsController@getIndex']);
        Route::put('dados-do-site/editar', ['as' => 'websiteSettingsPut', 'uses' => 'Admin\WebsiteSettingsController@putUpdate']);

        ## PROFILE
        Route::get('meus-dados', ['as' => 'profile', 'uses' => 'Admin\ProfileController@getIndex']);
        Route::put('meus-dados/editar', ['as' => 'profilePut', 'uses' => 'Admin\ProfileController@putUpdate']);

        ## USERS
        Route::get('usuarios', ['as' => 'users', 'uses' => 'Admin\UsersController@getIndex']);
        Route::get('usuarios/adicionar', ['as' => 'usersAdd', 'uses' => 'Admin\UsersController@getAdd']);
        Route::post('usuarios/adicionar', ['as' => 'usersAdd', 'uses' => 'Admin\UsersController@postAdd']);
        Route::get('usuarios/editar/{userId?}', ['as' => 'usersEdit', 'uses' => 'Admin\UsersController@getEdit']);
        Route::put('usuarios/editar', ['as' => 'usersEditPut', 'uses' => 'Admin\UsersController@putEdit']);
        Route::get('usuarios/permissoes/{userId?}', ['as' => 'usersPermissions', 'uses' => 'Admin\UsersController@getPermissions']);
        Route::post('usuarios/permissoes', ['as' => 'usersPermissionsPost', 'uses' => 'Admin\UsersController@postPermissions']);
        Route::delete('usuarios/excluir', ['as' => 'usersDelete', 'uses' => 'Admin\UsersController@delete']);

        ## LOGOUT
        Route::get('sair', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

        ##UPDATE ORDER
        Route::post('update-order', 'Admin\UpdateOrderController@postOrder');

    });
});