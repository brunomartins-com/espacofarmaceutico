<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Pages;
use App\Texts;
use App\EmailsContact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $page = 'contato';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        //STATES
        $statesConsult = \App\Exceptions\Handler::readFile("states.json");
        $states        = ['' => 'Estado'];
        foreach ($statesConsult as $state):
            $states[$state['name']] = $state['name'];
        endforeach;

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(2);

        return view('website.contact.index')->with(compact('page', 'websiteSettings', 'pages', 'states', 'text'));
    }

    public function send(Request $request)
    {
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $this->validate($request, [
            'name'       => 'required|max:100',
            'email'      => 'required|email|max:40',
            'message'    => 'required',
            'birthDate'  => 'required_if:peopleType,Física|max:10',
            'peopleType' => 'required',
            'zipCode'    => 'required',
            'address'    => 'required|max:120',
            'state'      => 'required',
            'city'       => 'required',
            'cpf'        => 'required_if:peopleType,Física|max:14',
            'cnpj'       => 'required_if:peopleType,Jurídica|max:18',
            'youAre'     => 'required_if:peopleType,Física',
            'phone'      => 'required_without:mobile',
            'mobile'     => 'required_without:phone',
        ],
            [
                'name.required'           => 'Informe seu nome',
                'name.max'                => 'O nome não pode passar de :max caracteres',
                'email.required'          => 'Informe seu e-mail',
                'email.email'             => 'Informe um e-mail válido',
                'email.max'               => 'O e-mail não pode passar de :max caracteres',
                'birthDate.required'      => 'Informe a data de nascimento',
                'birthDate.max'           => 'A data de nascimento não pode passar de :max caracteres',
                'peopleType.required'     => 'Informe o tipo de pessoa',
                'cpf.required_if'         => 'Informe o CPF',
                'cpf.max'                 => 'O CPF não pode passar de :max caracteres',
                'cnpj.required_if'        => 'Informe o CNPJ',
                'cnpj.max'                => 'O CNPJ não pode passar de :max caracteres',
                'youAre.required'         => 'Informe qual das informações você se enquadra',
                'phone.required_without'  => 'Informe um número de telefone',
                'mobile.required_without' => 'ou um número de celular',
                'message.required'        => 'Escreva uma mensagem',
            ]);

        array_set($request, "date", Carbon::now()->format('d/m/Y'));

        $emails = EmailsContact::find(1);

        Mail::send('website.contact.email', ['request' => $request], function ($message) use ($emails) {
            $emailsSend = explode(',', $emails->emails);
            foreach($emailsSend as $email){
                $message->from('webmaster@teuto.com.br', 'Teuto/Pfizer')
                    ->subject('Contato pelo Site [espacofarmaceutico.com.br]')
                    ->to($email);
            }
        });

        $success = "Contato enviado com sucesso!";
        return redirect(url('contato'))->with(compact('success'));
    }
}
