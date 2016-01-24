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

        ## REGISTERED
        Route::get('cadastrados', ['as' => 'registered', 'uses' => 'Admin\RegisteredController@getIndex']);
        Route::get('cadastrados/visualizar/{userId?}', ['as' => 'registeredView', 'uses' => 'Admin\RegisteredController@getView']);
        Route::put('cadastrados/status', ['as' => 'registeredStatus', 'uses' => 'Admin\RegisteredController@putStatus']);
        Route::delete('cadastrados/excluir', ['as' => 'registeredDelete', 'uses' => 'Admin\RegisteredController@delete']);

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

        ## TEUTO UNIVERSITY
        Route::get('universidade-teuto', ['as' => 'teutoUniversity', 'uses' => 'Admin\TeutoUniversityController@getIndex']);
        Route::put('universidade-teuto/editar', ['as' => 'teutoUniversityPut', 'uses' => 'Admin\TeutoUniversityController@putUpdate']);

        ## REGIONAL COUNCILS
        Route::get('conselhos-regionais', ['as' => 'regionalCouncils', 'uses' => 'Admin\RegionalCouncilsController@getIndex']);
        Route::put('conselhos-regionais/editar', ['as' => 'regionalCouncilsPut', 'uses' => 'Admin\RegionalCouncilsController@putUpdate']);

        ## YOUR BUSINESS MORE LUCRATIVE
        Route::get('seu-negocio-mais-lucrativo', ['as' => 'yourBusinessMoreLucrative', 'uses' => 'Admin\YourBusinessMoreLucrativeController@getIndex']);
        Route::put('seu-negocio-mais-lucrativo/editar', ['as' => 'yourBusinessMoreLucrativePut', 'uses' => 'Admin\YourBusinessMoreLucrativeController@putUpdate']);

        ## BULLA INSTITUTE
        Route::get('instituto-bulla', ['as' => 'bullaInstitute', 'uses' => 'Admin\BullaInstituteController@getIndex']);
        Route::put('instituto-bulla/editar', ['as' => 'bullaInstitutePut', 'uses' => 'Admin\BullaInstituteController@putUpdate']);

        ## RELIABILITY AND QUALITY
        Route::get('confiabilidade-e-qualidade', ['as' => 'reliabilityAndQuality', 'uses' => 'Admin\ReliabilityAndQualityController@getIndex']);
        Route::get('confiabilidade-e-qualidade/adicionar', ['as' => 'reliabilityAndQualityAdd', 'uses' => 'Admin\ReliabilityAndQualityController@getAdd']);
        Route::post('confiabilidade-e-qualidade/adicionar', ['as' => 'reliabilityAndQualityAdd', 'uses' => 'Admin\ReliabilityAndQualityController@postAdd']);
        Route::get('confiabilidade-e-qualidade/editar/{reliabilityAndQualityId?}', ['as' => 'reliabilityAndQualityEdit', 'uses' => 'Admin\ReliabilityAndQualityController@getEdit']);
        Route::put('confiabilidade-e-qualidade/editar', ['as' => 'reliabilityAndQualityEditPut', 'uses' => 'Admin\ReliabilityAndQualityController@putEdit']);
        Route::get('confiabilidade-e-qualidade/ordenar', ['as' => 'reliabilityAndQualityOrder', 'uses' => 'Admin\ReliabilityAndQualityController@getOrder']);
        Route::delete('confiabilidade-e-qualidade/excluir', ['as' => 'reliabilityAndQualityDelete', 'uses' => 'Admin\ReliabilityAndQualityController@delete']);

        ## WORK WITH US
        Route::get('trabalhe-conosco', ['as' => 'workWithUs', 'uses' => 'Admin\WorkWithUsController@getIndex']);
        Route::put('trabalhe-conosco/editar', ['as' => 'workWithUsPut', 'uses' => 'Admin\WorkWithUsController@putUpdate']);

        ## WORK WITH US - VACANCY
        Route::get('trabalhe-conosco/vagas', ['as' => 'workWithUsVacancies', 'uses' => 'Admin\WorkWithUsVacanciesController@getIndex']);
        Route::get('trabalhe-conosco/vagas/adicionar', ['as' => 'workWithUsVacanciesAdd', 'uses' => 'Admin\WorkWithUsVacanciesController@getAdd']);
        Route::post('trabalhe-conosco/vagas/adicionar', ['as' => 'workWithUsVacanciesAdd', 'uses' => 'Admin\WorkWithUsVacanciesController@postAdd']);
        Route::get('trabalhe-conosco/vagas/editar/{workWithUsVacanciesId?}', ['as' => 'workWithUsVacanciesEdit', 'uses' => 'Admin\WorkWithUsVacanciesController@getEdit']);
        Route::put('trabalhe-conosco/vagas/editar', ['as' => 'workWithUsVacanciesEditPut', 'uses' => 'Admin\WorkWithUsVacanciesController@putEdit']);
        Route::get('trabalhe-conosco/vagas/ordenar', ['as' => 'workWithUsVacanciesOrder', 'uses' => 'Admin\WorkWithUsVacanciesController@getOrder']);
        Route::delete('trabalhe-conosco/vagas/excluir', ['as' => 'workWithUsVacanciesDelete', 'uses' => 'Admin\WorkWithUsVacanciesController@delete']);

        ## CONCEPT
        Route::get('conceito', ['as' => 'concept', 'uses' => 'Admin\ConceptController@getIndex']);
        Route::get('conceito/adicionar', ['as' => 'conceptAdd', 'uses' => 'Admin\ConceptController@getAdd']);
        Route::post('conceito/adicionar', ['as' => 'conceptAdd', 'uses' => 'Admin\ConceptController@postAdd']);
        Route::get('conceito/editar/{conceptId?}', ['as' => 'conceptEdit', 'uses' => 'Admin\ConceptController@getEdit']);
        Route::put('conceito/editar', ['as' => 'conceptEditPut', 'uses' => 'Admin\ConceptController@putEdit']);
        Route::get('conceito/ordenar', ['as' => 'conceptOrder', 'uses' => 'Admin\ConceptController@getOrder']);
        Route::delete('conceito/excluir', ['as' => 'conceptDelete', 'uses' => 'Admin\ConceptController@delete']);

        ## REDUCING SPENDING ON HEALTH
        Route::get('reducao-de-gastos-com-a-saude', ['as' => 'reducingSpendingOnHealth', 'uses' => 'Admin\ReducingSpendingOnHealthController@getIndex']);
        Route::get('reducao-de-gastos-com-a-saude/adicionar', ['as' => 'reducingSpendingOnHealthAdd', 'uses' => 'Admin\ReducingSpendingOnHealthController@getAdd']);
        Route::post('reducao-de-gastos-com-a-saude/adicionar', ['as' => 'reducingSpendingOnHealthAdd', 'uses' => 'Admin\ReducingSpendingOnHealthController@postAdd']);
        Route::get('reducao-de-gastos-com-a-saude/editar/{reducingSpendingOnHealthId?}', ['as' => 'reducingSpendingOnHealthEdit', 'uses' => 'Admin\ReducingSpendingOnHealthController@getEdit']);
        Route::put('reducao-de-gastos-com-a-saude/editar', ['as' => 'reducingSpendingOnHealthEditPut', 'uses' => 'Admin\ReducingSpendingOnHealthController@putEdit']);
        Route::get('reducao-de-gastos-com-a-saude/ordenar', ['as' => 'reducingSpendingOnHealthOrder', 'uses' => 'Admin\ReducingSpendingOnHealthController@getOrder']);
        Route::delete('reducao-de-gastos-com-a-saude/excluir', ['as' => 'reducingSpendingOnHealthDelete', 'uses' => 'Admin\ReducingSpendingOnHealthController@delete']);

        ## COMMON QUESTIONS
        Route::get('perguntas-frequentes', ['as' => 'commonQuestions', 'uses' => 'Admin\CommonQuestionsController@getIndex']);
        Route::get('perguntas-frequentes/adicionar', ['as' => 'commonQuestionsAdd', 'uses' => 'Admin\CommonQuestionsController@getAdd']);
        Route::post('perguntas-frequentes/adicionar', ['as' => 'commonQuestionsAdd', 'uses' => 'Admin\CommonQuestionsController@postAdd']);
        Route::get('perguntas-frequentes/editar/{commonQuestionsId?}', ['as' => 'commonQuestionsEdit', 'uses' => 'Admin\CommonQuestionsController@getEdit']);
        Route::put('perguntas-frequentes/editar', ['as' => 'commonQuestionsEditPut', 'uses' => 'Admin\CommonQuestionsController@putEdit']);
        Route::get('perguntas-frequentes/ordenar', ['as' => 'commonQuestionsOrder', 'uses' => 'Admin\CommonQuestionsController@getOrder']);
        Route::delete('perguntas-frequentes/excluir', ['as' => 'commonQuestionsDelete', 'uses' => 'Admin\CommonQuestionsController@delete']);

        ## LEGISLATION
        Route::get('legislacao', ['as' => 'legislation', 'uses' => 'Admin\LegislationController@getIndex']);
        Route::get('legislacao/adicionar', ['as' => 'legislationAdd', 'uses' => 'Admin\LegislationController@getAdd']);
        Route::post('legislacao/adicionar', ['as' => 'legislationAdd', 'uses' => 'Admin\LegislationController@postAdd']);
        Route::get('legislacao/editar/{legislationId?}', ['as' => 'legislationEdit', 'uses' => 'Admin\LegislationController@getEdit']);
        Route::put('legislacao/editar', ['as' => 'legislationEditPut', 'uses' => 'Admin\LegislationController@putEdit']);
        Route::get('legislacao/ordenar', ['as' => 'legislationOrder', 'uses' => 'Admin\LegislationController@getOrder']);
        Route::delete('legislacao/excluir', ['as' => 'legislationDelete', 'uses' => 'Admin\LegislationController@delete']);
        Route::get('legislacao/texto', ['as' => 'legislationText', 'uses' => 'Admin\LegislationController@getText']);
        Route::put('legislacao/texto/editar', ['as' => 'legislationTextPut', 'uses' => 'Admin\LegislationController@putTextUpdate']);

        ## FARMACOVIGILANCE
        Route::get('farmacovigilancia', ['as' => 'farmacovigilance', 'uses' => 'Admin\FarmacovigilanceController@getIndex']);
        Route::put('farmacovigilancia/editar', ['as' => 'farmacovigilancePut', 'uses' => 'Admin\FarmacovigilanceController@putUpdate']);

        ## CONTACT
        Route::get('contato', ['as' => 'contact', 'uses' => 'Admin\ContactController@getIndex']);
        Route::put('contato/editar', ['as' => 'contactPut', 'uses' => 'Admin\ContactController@putUpdate']);

        ## VISIT WELL
        Route::get('visite-bem', ['as' => 'visitWell', 'uses' => 'Admin\VisitWellController@getIndex']);
        Route::put('visite-bem/editar', ['as' => 'visitWellPut', 'uses' => 'Admin\VisitWellController@putUpdate']);
        ## VISIT WELL PHOTOS
        Route::get('visite-bem/fotos', ['as' => 'visitWellPhotos', 'uses' => 'Admin\VisitWellPhotosController@getIndex']);
        Route::get('visite-bem/fotos/adicionar', ['as' => 'visitWellPhotosAdd', 'uses' => 'Admin\VisitWellPhotosController@getAdd']);
        Route::post('visite-bem/fotos/adicionar', ['as' => 'visitWellPhotosAdd', 'uses' => 'Admin\VisitWellPhotosController@postAdd']);
        Route::get('visite-bem/fotos/editar/{visitWellPhotosId?}', ['as' => 'visitWellPhotosEdit', 'uses' => 'Admin\VisitWellPhotosController@getEdit']);
        Route::put('visite-bem/fotos/editar', ['as' => 'visitWellPhotosEditPut', 'uses' => 'Admin\VisitWellPhotosController@putEdit']);
        Route::delete('visite-bem/fotos/excluir', ['as' => 'visitWellPhotosDelete', 'uses' => 'Admin\VisitWellPhotosController@delete']);

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
        Route::delete('produtos/categorias/excluir', ['as' => 'productsCategoriesDelete', 'uses' => 'Admin\ProductsController@deleteCategories']);

        ## DIGITAL CATALOGS
        Route::get('catalogos-digitais', ['as' => 'digitalCatalogs', 'uses' => 'Admin\DigitalCatalogsController@getIndex']);
        Route::get('catalogos-digitais/adicionar', ['as' => 'digitalCatalogsAdd', 'uses' => 'Admin\DigitalCatalogsController@getAdd']);
        Route::post('catalogos-digitais/adicionar', ['as' => 'digitalCatalogsAdd', 'uses' => 'Admin\DigitalCatalogsController@postAdd']);
        Route::get('catalogos-digitais/editar/{digitalCatalogsId?}', ['as' => 'digitalCatalogsEdit', 'uses' => 'Admin\DigitalCatalogsController@getEdit']);
        Route::put('catalogos-digitais/editar', ['as' => 'digitalCatalogsEditPut', 'uses' => 'Admin\DigitalCatalogsController@putEdit']);
        Route::get('catalogos-digitais/ordenar', ['as' => 'digitalCatalogsOrder', 'uses' => 'Admin\DigitalCatalogsController@getOrder']);
        Route::delete('catalogos-digitais/excluir', ['as' => 'digitalCatalogsDelete', 'uses' => 'Admin\DigitalCatalogsController@delete']);

        ## EVENTS
        Route::get('eventos', ['as' => 'events', 'uses' => 'Admin\EventsController@getIndex']);
        Route::get('eventos/adicionar', ['as' => 'eventsAdd', 'uses' => 'Admin\EventsController@getAdd']);
        Route::post('eventos/adicionar', ['as' => 'eventsAdd', 'uses' => 'Admin\EventsController@postAdd']);
        Route::get('eventos/editar/{eventsId?}', ['as' => 'eventsEdit', 'uses' => 'Admin\EventsController@getEdit']);
        Route::put('eventos/editar', ['as' => 'eventsEditPut', 'uses' => 'Admin\EventsController@putEdit']);
        Route::delete('eventos/excluir', ['as' => 'eventsDelete', 'uses' => 'Admin\EventsController@delete']);

        ## APPS
        Route::get('aplicativos', ['as' => 'apps', 'uses' => 'Admin\AppsController@getIndex']);
        Route::get('aplicativos/adicionar', ['as' => 'appsAdd', 'uses' => 'Admin\AppsController@getAdd']);
        Route::post('aplicativos/adicionar', ['as' => 'appsAdd', 'uses' => 'Admin\AppsController@postAdd']);
        Route::get('aplicativos/editar/{appsId?}', ['as' => 'appsEdit', 'uses' => 'Admin\AppsController@getEdit']);
        Route::put('aplicativos/editar', ['as' => 'appsEditPut', 'uses' => 'Admin\AppsController@putEdit']);
        Route::get('aplicativos/ordenar', ['as' => 'appsOrder', 'uses' => 'Admin\AppsController@getOrder']);
        Route::delete('aplicativos/excluir', ['as' => 'appsDelete', 'uses' => 'Admin\AppsController@delete']);

        ## 3D MOVIE
        Route::get('videos-3d', ['as' => 'movies3D', 'uses' => 'Admin\Movies3DController@getIndex']);
        Route::get('videos-3d/adicionar', ['as' => 'movies3DAdd', 'uses' => 'Admin\Movies3DController@getAdd']);
        Route::post('videos-3d/adicionar', ['as' => 'movies3DAdd', 'uses' => 'Admin\Movies3DController@postAdd']);
        Route::get('videos-3d/editar/{movies3DId?}', ['as' => 'movies3DEdit', 'uses' => 'Admin\Movies3DController@getEdit']);
        Route::put('videos-3d/editar', ['as' => 'movies3DEditPut', 'uses' => 'Admin\Movies3DController@putEdit']);
        Route::delete('videos-3d/excluir', ['as' => 'movies3DDelete', 'uses' => 'Admin\Movies3DController@delete']);

        ## BLOG
        Route::get('blog', ['as' => 'blog', 'uses' => 'Admin\BlogController@getIndex']);
        Route::get('blog/adicionar', ['as' => 'blogAdd', 'uses' => 'Admin\BlogController@getAdd']);
        Route::post('blog/adicionar', ['as' => 'blogAdd', 'uses' => 'Admin\BlogController@postAdd']);
        Route::get('blog/editar/{blogId?}', ['as' => 'blogEdit', 'uses' => 'Admin\BlogController@getEdit']);
        Route::put('blog/editar', ['as' => 'blogEditPut', 'uses' => 'Admin\BlogController@putEdit']);
        Route::delete('blog/excluir', ['as' => 'blogDelete', 'uses' => 'Admin\BlogController@delete']);

        ## NEWS AND RELEASES
        Route::get('noticias-e-releases', ['as' => 'newsAndReleases', 'uses' => 'Admin\NewsAndReleasesController@getIndex']);
        Route::get('noticias-e-releases/adicionar', ['as' => 'newsAndReleasesAdd', 'uses' => 'Admin\NewsAndReleasesController@getAdd']);
        Route::post('noticias-e-releases/adicionar', ['as' => 'newsAndReleasesAdd', 'uses' => 'Admin\NewsAndReleasesController@postAdd']);
        Route::get('noticias-e-releases/editar/{newsAndReleasesId?}', ['as' => 'newsAndReleasesEdit', 'uses' => 'Admin\NewsAndReleasesController@getEdit']);
        Route::put('noticias-e-releases/editar', ['as' => 'newsAndReleasesEditPut', 'uses' => 'Admin\NewsAndReleasesController@putEdit']);
        Route::delete('noticias-e-releases/excluir', ['as' => 'newsAndReleasesDelete', 'uses' => 'Admin\NewsAndReleasesController@delete']);

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

        ##CATEGORY
        Route::get('select-category/{modalTitle?}/{modalName?}/{modalDatabaseTable?}', ['as' => 'selectCategory', 'uses' => 'Admin\CategoryModalController@getIndex']);
        Route::post('select-category/add', ['as' => 'selectCategoryAdd', 'uses' => 'Admin\CategoryModalController@postAdd']);
        Route::put('select-category/edit', ['as' => 'selectCategoryEdit', 'uses' => 'Admin\CategoryModalController@putEdit']);
        Route::delete('select-category/delete', ['as' => 'selectCategoryDelete', 'uses' => 'Admin\CategoryModalController@delete']);
        Route::post('select-category/refresh', ['as' => 'selectCategoryRefresh', 'uses' => 'Admin\CategoryModalController@postRefresh']);
    });
});