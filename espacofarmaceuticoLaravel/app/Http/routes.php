<?php

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

## CONSELHOS REGIONAIS
Route::get('conselhos-regionais', 'Website\RegionalCouncilsController@index');

## SEU NEGOCIO MAIS LUCRATIVO
Route::get('seu-negocio-mais-lucrativo', 'Website\YouBusinessMoreLucrativeController@index');

## INSTITUTO BULLA
Route::get('instituto-bulla', 'Website\BullaInstituteController@index');

## VISITE BEM
Route::group(['prefix' => 'visite-bem'], function() {
    ## SOBRE O VISITE BEM
    Route::get('/', 'Website\VisitWellController@index');
    ## FOTOS
    Route::get('fotos', 'Website\VisitWellController@photos');
    ## AGENDE SUA VISITA
    Route::get('agende-sua-visita', 'Website\VisitWellController@scheduleYourVisit');
});



## RECOVERY PASSWORD
Route::post('recuperar-senha', 'Auth\PasswordController@postEmail');
Route::get('recuperar-senha/{token}', ['as' => 'passwordReset', 'uses' => 'Auth\PasswordController@getResetWebsite']);
Route::post('recuperar-senha/nova', ['as' => 'passwordReset', 'uses' => 'Auth\PasswordController@postResetWebsite']);

## LOGIN WEBSITE
Route::post('login', 'Auth\AuthController@postLoginWebsite');

## PROFILE
Route::put('profile', 'Website\ProfileController@putUpdate');

## PHOTOS AND VIDEO
Route::post('photos', 'Website\HomeController@postSentPhotos');
Route::post('video', 'Website\HomeController@postSentVideo');
Route::post('receipts', 'Website\HomeController@postSentReceipts');

## UPLOAD
Route::post('upload', 'Website\HomeController@postUpload');
Route::post('upload-cupons', 'Website\HomeController@postUploadReceipts');

## LOGOUT WEBSITE
Route::get('sair', 'Auth\AuthController@getLogout');

## NEWSLETTER
Route::post('newsletter', 'Website\NewsletterController@post');

## THE COMPETITION
Route::get('o-concurso', 'Website\TheCompetitionController@index');

## REGULATION
Route::get('regulamento', 'Website\RegulationController@index');

## AWARDS
Route::get('premios', 'Website\AwardsController@index');

## WINNERS 2014
Route::get('ganhadores-2014', 'Website\Winners2014Controller@index');

## CONTACT
Route::get('contato', 'Website\ContactController@index');
Route::post('contato', 'Website\ContactController@post');

## CITIES
Route::post('cities', 'Website\CitiesController@post');

## PRODUCTS
Route::get('produtos-participantes', 'Website\ProductsController@index');

## REGISTRATION
Route::get('inscricao', 'Website\RegistrationController@index');
Route::post('inscricao', 'Auth\AuthController@postRegister');
Route::get('inscricao/confirmacao/{token?}', 'Website\RegistrationController@getConfirmation');

## FINALISTS
Route::get('finalistas', 'Website\FinalistsController@index');

## WINNERS
Route::get('vencedores', 'Website\WinnersController@index');

Route::group(['middleware' => 'guest'], function () {

    // Login - Logout routes
    //Route::get('login', 'Auth\AuthController@getLogin');
    //Route::post('login', 'Auth\AuthController@postLogin');

    /*// PASSWORD RESET LINK REQUEST
    Route::get('admin/senha/email', ['as' => 'passwordEmail', 'uses' => 'Auth\PasswordController@getEmail']);
    Route::post('admin/senha/email', ['as' => 'passwordEmail', 'uses' => 'Auth\PasswordController@postEmail']);
    // PASSWORD RESET
    Route::get('recuperar-senha/{token}', ['as' => 'passwordReset', 'uses' => 'Auth\PasswordController@getReset']);
    Route::post('senha/nova', ['as' => 'passwordReset', 'uses' => 'Auth\PasswordController@postReset']);*/


    /*// Password reset link request routes
    Route::get('forgot-password', 'Auth\PasswordController@getEmail');
    Route::post('forgot-password', 'Auth\PasswordController@postEmail');

    // Password reset routes
    Route::get('recuperar-senha/{token}', 'Auth\PasswordController@getReset');
    Route::post('recuperar-senha', 'Auth\PasswordController@postReset');*/

});


// AUTHENTICATION
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

    ## PHOTOS
    Route::get('fotos', ['as' => 'photos', 'uses' => 'Admin\PhotosController@getIndex']);
    Route::put('fotos/status', ['as' => 'photosStatus', 'uses' => 'Admin\PhotosController@putStatus']);
    Route::get('fotos/finalista/{photosId?}', ['as' => 'photosFinalist', 'uses' => 'Admin\PhotosController@getFinalist']);
    Route::put('fotos/finalista', ['as' => 'photosEdit', 'uses' => 'Admin\PhotosController@putEdit']);
    Route::delete('fotos/excluir', ['as' => 'photosDelete', 'uses' => 'Admin\PhotosController@delete']);

    ## VIDEOS
    Route::get('videos', ['as' => 'videos', 'uses' => 'Admin\VideosController@getIndex']);
    Route::put('videos/status', ['as' => 'videosStatus', 'uses' => 'Admin\VideosController@putStatus']);
    Route::get('videos/finalista/{videosId?}', ['as' => 'videosFinalist', 'uses' => 'Admin\VideosController@getFinalist']);
    Route::put('videos/finalista', ['as' => 'videosEdit', 'uses' => 'Admin\VideosController@putEdit']);
    Route::delete('videos/excluir', ['as' => 'videosDelete', 'uses' => 'Admin\VideosController@delete']);

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

    ## ADVERTISING
    Route::get('propagandas', ['as' => 'advertising', 'uses' => 'Admin\AdvertisingController@getIndex']);
    Route::get('propagandas/adicionar', ['as' => 'advertisingAdd', 'uses' => 'Admin\AdvertisingController@getAdd']);
    Route::post('propagandas/adicionar', ['as' => 'advertisingAdd', 'uses' => 'Admin\AdvertisingController@postAdd']);
    Route::get('propagandas/editar/{bannersId?}', ['as' => 'advertisingEdit', 'uses' => 'Admin\AdvertisingController@getEdit']);
    Route::put('propagandas/editar', ['as' => 'advertisingEditPut', 'uses' => 'Admin\AdvertisingController@putEdit']);
    Route::delete('propagandas/excluir', ['as' => 'advertisingDelete', 'uses' => 'Admin\AdvertisingController@delete']);

    ## THE COMPETITION
    Route::get('o-concurso', ['as' => 'theCompetition', 'uses' => 'Admin\TheCompetitionController@getIndex']);
    Route::put('o-concurso/editar', ['as' => 'theCompetitionPut', 'uses' => 'Admin\TheCompetitionController@putUpdate']);

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
    Route::get('produtos/ordenar', ['as' => 'productsOrder', 'uses' => 'Admin\ProductsController@getOrder']);
    Route::delete('produtos/excluir', ['as' => 'productsDelete', 'uses' => 'Admin\ProductsController@delete']);

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