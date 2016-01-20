<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Pages;
use App\User;

class RegistrationController extends Controller
{
    public function index()
    {
        $page = 'cadastre-se';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        //STATES
        $statesConsult = \App\Exceptions\Handler::readFile("states.json");
        $states = ['' => 'Estado'];
        foreach($statesConsult as $state):
            $states[$state['name']] = $state['name'];
        endforeach;

        $pages = Pages::where('slug', '=', $page)->first();

        return view('website.registration.index')->with(compact('page', 'websiteSettings', 'pages', 'states'));
    }

    public function getConfirmation(Request $request)
    {
        User::where('token', '=', $request->token)->update(['active' => 1]);

        $message = "Cadastro confirmado com sucesso. Você já pode efetuar login e aproveitar nosso conteúdo exclusivo!";
        return redirect('/')->with(compact('message'));
    }
}