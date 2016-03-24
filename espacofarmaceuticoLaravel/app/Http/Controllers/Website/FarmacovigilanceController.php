<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

use App\Texts;
use App\Pages;
use App\Newsletter;
use App\EmailsFarmacovigilance;

class FarmacovigilanceController extends Controller
{
    public function index()
    {
        $page = 'farmacovigilancia';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(6);

        return view('website.farmacovigilance.index')->with(compact('page', 'websiteSettings', 'pages', 'text'));
    }

    public function send(Request $request)
    {
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $this->validate($request, [
            'patientName'           => 'required|max:100',
            'weight'                => 'required|max:5',
            'height'                => 'required|max:4',
            'reactionDescription'   => 'required',
            'birthDate'             => 'required|max:10',
            'gender'                => 'required',
            'productReaction'       => 'required|max:100',
            'productReasonOfUse'    => 'required',
            'productBatch'          => 'required|max:100',
            'notifierName'          => 'required|max:100',
            'peopleType'            => 'required',
            'cpf'                   => 'required_if:peopleType,Física|max:14',
            'notifierBirthDate'     => 'required_if:peopleType,Física|max:10',
            'cnpj'                  => 'required_if:peopleType,Jurídica|max:18',
            'youAre'                => 'required',
            'phone'                 => 'required_without:mobile',
            'mobile'                => 'required_without:phone',
            'email'                 => 'required|email|max:40',
            'newsletter'            => 'required'
        ],
        [
            'patientName.required'          => 'Informe o nome do paciente',
            'patientName.max'               => 'O nome do paciente não pode passar de :max caracteres',
            'weight.required'               => 'Informe o peso do paciente',
            'weight.max'                    => 'O peso do paciente não pode passar de :max caracteres',
            'height.required'               => 'Informe a altura do paciente',
            'height.max'                    => 'A altura do paciente não pode passar de :max caracteres',
            'reactionDescription.required'  => 'Descreva a reação do paciente',
            'birthDate.required'            => 'Informe a data de nascimento do paciente',
            'birthDate.max'                 => 'A data de nascimento do paciente não pode passar de :max caracteres',
            'gender.required'               => 'Marque o sexo do paciente',
            'productReaction.required'      => 'Informe o produto que causou a reação',
            'productReaction.max'           => 'O nome do produto não pode passar de :max caracteres',
            'productReasonOfUse.required'   => 'Informe o motivo do uso do produto',
            'productBatch.required'         => 'Informe o lote do produto',
            'productBatch.max'              => 'O lote do produto não pode passar de :max caracteres',
            'notifierName.required'         => 'Informe o nome do notificador',
            'notifierName.max'              => 'O nome do notificador não pode passar de :max caracteres',
            'peopleType.required'           => 'Informe o tipo de pessoa',
            'cpf.required_if'               => 'Informe o CPF do notificador',
            'cpf.max'                       => 'O CPF do notificador não pode passar de :max caracteres',
            'notifierBirthDate.required_if' => 'Informe da data de nascimento do notificador',
            'notifierBirthDate.max'         => 'A data de nascimento do notificador não pode passar de :max caracteres',
            'cnpj.required_if'              => 'Informe o CNPJ do notificador',
            'cnpj.max'                      => 'O CNPJ do notificador não pode passar de :max caracteres',
            'youAre.required'               => 'Informe qual das informações você se enquadra',
            'phone.required_without'        => 'Informe um número de telefone',
            'mobile.required_without'       => 'ou um número de celular',
            'email.required'                => 'Informe um e-mail para contato',
            'email.email'                   => 'Informe um e-mail válido',
            'email.max'                     => 'O e-mail não pode passar de :max caracteres',
            'newsletter.required'           => 'Marque se você deseja ou não receber nossos e-mails'
        ]);

        if($request->newsletter == 'Sim'){
            $newsletterConsult = Newsletter::where('email', '=', $request->email)->count();
            if($newsletterConsult == 0) {
                $newsletter = new Newsletter();
                $newsletter->email = $request->email;
                $newsletter->save();
            }
        }

        array_set($request, "date", Carbon::now()->format('d/m/Y'));

        $emails = EmailsFarmacovigilance::find(1);

        Mail::send('website.farmacovigilance.email', ['request' => $request], function ($message) use ($emails) {
            $emailsSend = explode(',', $emails->emails);
            foreach($emailsSend as $email){
                $message->from('webmaster@teuto.com.br', 'Teuto/Pfizer')
                    ->subject('Farmacovigilância [espacofarmaceutico.com.br]')
                    ->to($email);
            }
        });

        $success = "E-mail enviado com sucesso!";
        return redirect(url('farmacovigilancia'))->with(compact('success'));
    }
}