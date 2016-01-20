<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Pages;
use App\User;
use Auth;
use App\Cities;

class ProfileController extends Controller
{
    public function getData()
    {
        $page = 'meus-dados';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $checkedGenderMale = "";
        $checkedGenderFemale = "";
        $checkedNewsletterYes = "";
        $checkedNewsletterNo = "";
        if(Auth::getUser()->gender == 'Masculino'){
            $checkedGenderMale = true;
        }else{
            $checkedGenderFemale = true;
        }
        if(Auth::getUser()->newsletter == 1){
            $checkedNewsletterYes = true;
        }else{
            $checkedNewsletterNo = true;
        }
        $birthDateOriginal = explode('-', Auth::getUser()->birthDate);
        $birthDate = $birthDateOriginal[2]."/".$birthDateOriginal[1]."/".$birthDateOriginal[0];

        return view('website.profile.data')->with(compact('page', 'websiteSettings', 'pages', 'checkedGenderMale', 'checkedGenderFemale', 'birthDate', 'checkedNewsletterYes', 'checkedNewsletterNo'));
    }

    public function putDataUpdate(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|max:100',
            'crf'           => 'required|max:30',
            'gender'        => 'required',
            'birthDate'     => 'required|max:10',
            'newsletter'    => 'required',
            'password'      => 'confirmed|min:6|max:16'
        ],
        [
            'name.required'             => 'Informe seu nome',
            'name.max'                  => 'O nome não deve ser maior que :max caracteres',
            'email.required'            => 'O e-mail é obrigatório',
            'email.email'               => 'O e-mail é inválido.',
            'email.unique'              => 'O e-mail já está cadastrado.',
            'crf.required'              => 'Informe seu CRF',
            'crf.max'                   => 'O CRF não deve ser maior que :max caracteres',
            'gender.required'           => 'Informe o seu sexo',
            'newsletter.required'       => 'Marque se você deseja ou não receber nossos e-mails',
            'password.min'              => 'A senha não deve ser menor que :min caracteres',
            'password.max'              => 'A senha não deve ser maior que :max caracteres',
            'password.confirmed'        => 'As senhas não conferem'
        ]);

        $user = User::find(Auth::getUser()->id);
        $user->name = $request->name;
        $user->crf = $request->crf;
        $user->gender = $request->gender;
        $user->birthDate = Carbon::createFromFormat('d/m/Y', $request->birthDate)->format('Y-m-d');
        $user->newsletter = $request->newsletter;
        if($request->password) {
            $user->password = md5($request->password);
        }
        $user->save();

        $success = "Dados alterados com sucesso.";

        return redirect('meus-dados')->with(compact('success'));
    }

    public function getAddress()
    {
        $page = 'meu-endereco';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        //STATES
        $statesConsult = \App\Exceptions\Handler::readFile("states.json");
        $states = ['' => 'Estado'];
        foreach($statesConsult as $state):
            $states[$state['name']] = $state['name'];
        endforeach;

        //CITIES
        $citiesConsult = Cities::where('uf', '=', ''.Auth::getUser()->state.'')->get();
        $cities = ['' => 'Cidade'];
        foreach($citiesConsult as $city):
            $cities[$city['name']] = $city['name'];
        endforeach;

        $pages = Pages::where('slug', '=', $page)->first();

        return view('website.profile.address')->with(compact('page', 'websiteSettings', 'pages', 'states', 'cities'));
    }

    public function putAddressUpdate(Request $request)
    {
        $this->validate($request, [
            'zipCode'       => 'required|numeric',
            'address'       => 'required|max:200',
            'state'         => 'required',
            'city'          => 'required'
        ],
        [
            'zipCode.required'          => 'Informe o seu CEP',
            'zipCode.numeric'           => 'Somente números são aceitos no CEP',
            'address.required'          => 'O endereço é obrigatório',
            'address.max'               => 'O endereço não deve ser maior que :max caracteres',
            'state.required'            => 'É preciso escolher um Estado',
            'city.required'             => 'É preciso escolher uma cidade'
        ]);

        $user = User::find(Auth::getUser()->id);
        $user->zipCode = $request->zipCode;
        $user->address = $request->address;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->save();

        $success = "Endereço alterado com sucesso.";

        return redirect('meu-endereco')->with(compact('success'));
    }
}