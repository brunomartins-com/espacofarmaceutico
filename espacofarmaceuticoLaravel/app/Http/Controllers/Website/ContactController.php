<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

use App\Texts;
use App\Pages;

class ContactController extends Controller
{
    public function index()
    {
        $page = 'contato';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(2);

        return view('website.contact.index')->with(compact('page', 'websiteSettings', 'pages', 'text'));
    }

    public function send(Request $request)
    {
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $this->validate($request, [
            'name'         => 'required|max:100',
            'email'        => 'required|email|max:40',
            'message'      => 'required'
        ],
        [
            'name.required'             => 'Informe seu nome',
            'name.max'                  => 'O nome não pode passar de :max caracteres',
            'email.required'            => 'Informe seu e-mail',
            'email.email'               => 'Informe um e-mail válido',
            'email.max'                 => 'O e-mail não pode passar de :max caracteres',
            'message.required'          => 'Escreva uma mensagem'
        ]);

        array_set($request, "date", Carbon::now()->format('d/m/Y'));

        Mail::send('website.contact.email', ['request' => $request], function ($message) use ($websiteSettings) {
            $message->from('webmaster@teuto.com.br', 'Teuto/Pfizer')
                ->subject('Contato pelo Site [espacofarmaceutico.com.br]')
                //->to($websiteSettings['email'])
                ->to('hello@josevieira.me');
        });

        $success = "Contato enviado com sucesso!";
        return redirect(url('contato'))->with(compact('success'));
    }
}