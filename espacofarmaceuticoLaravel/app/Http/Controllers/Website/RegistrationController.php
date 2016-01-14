<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

use App\Advertising;
use App\Calls;
use App\Pages;
use App\User;

class RegistrationController extends Controller
{
    public function index()
    {
        $page = 'inscricao';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        if($websiteSettings['registerOk'] == 0){
            $message = "A página que você tentou acessar está indisponível no momento ou não existe";
            return redirect('/')->with(compact('message'));
        }

        //STATES
        $statesConsult = \App\Exceptions\Handler::readFile("states.json");
        $states = ['' => 'UF'];
        foreach($statesConsult as $state):
            $states[$state['uf']] = $state['uf'];
        endforeach;

        $pages = Pages::where('slug', '=', $page)->first();
        $advertising = Advertising::orderByRaw("RAND()")->get();
        foreach($advertising as $ad){
            array_add($ad, "image", Advertising::imageVideo($ad->url));
            array_set($ad, "url", Advertising::embedVideo($ad->url, 1));
        }
        $calls = Calls::orderByRaw("RAND()")->limit(2)->get();

        return view('website.registration')->with(compact('page', 'websiteSettings', 'pages', 'advertising', 'calls', 'states'));
    }

    public function getConfirmation(Request $request)
    {
        User::where('token', '=', $request->token)->update(['active' => 1]);

        $message = "Cadastro confirmado com sucesso. Você já pode efetuar login enviar suas fotos e/ou vídeo e boa sorte!";
        return redirect('/')->with(compact('message'));
    }
}