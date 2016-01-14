<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Carbon\Carbon;

use App\Advertising;
use App\Calls;
use App\Pages;

class ContactController extends Controller
{
    public function index()
    {
        $page = 'contato';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");
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

        return view('website.contact')->with(compact('page', 'websiteSettings', 'pages', 'advertising', 'calls', 'states'));
    }

    public function post(Request $request)
    {
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $this->validate($request, [
            'name'         => 'required|max:100',
            'email'        => 'required|email|max:40',
            'state'        => 'required',
            'city'         => 'required',
            'message'      => 'required'
        ]);
        array_set($request, "date", Carbon::now()->format('d/m/Y'));

        Mail::send('template.emailContact', ['request' => $request], function ($message) use ($websiteSettings) {
            $message->from('webmaster@teuto.com.br', 'Teuto/Pfizer')
                ->subject('Contato pelo Site [hipodermeomega.com.br]')
                ->to($websiteSettings['email']);
        });

        $success = "Contato enviado com sucesso!";
        return redirect(url('contato'))->with(compact('success'));
    }
}