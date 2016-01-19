<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Carbon\Carbon;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => 'required|max:100',
            'email'         => 'required|email|max:40|unique:users,email,NULL,id,type,1',
            'crf'           => 'required|max:30',
            'gender'        => 'required',
            'birthDate'     => 'required|max:10',
            'zipCode'       => 'required|numeric',
            'address'       => 'required|max:200',
            'state'         => 'required',
            'city'          => 'required',
            'newsletter'    => 'required',
            'password'      => 'required|confirmed|min:6|max:16'
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
            'birthDate.required'        => 'Informe a data de nascimento',
            'birthDate.max'             => 'A data de nascimento não deve ser maior que :max caracteres',
            'zipCode.required'          => 'Informe o seu CEP',
            'zipCode.numeric'           => 'Somente números são aceitos no CEP',
            'address.required'          => 'O endereço é obrigatório',
            'address.max'               => 'O endereço não deve ser maior que :max caracteres',
            'state.required'            => 'É preciso escolher um Estado',
            'city.required'             => 'É preciso escolher uma cidade',
            'newsletter.required'       => 'Marque se você deseja ou não receber nossos e-mails',
            'password.required'         => 'Informe uma senha',
            'password.min'              => 'A senha não deve ser menor que :min caracteres',
            'password.max'              => 'A senha não deve ser maior que :max caracteres',
            'password.confirmed'        => 'As senhas não conferem'
        ]);
    }

    /**
     * Create a new user for website instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function createUser(array $data)
    {
        return User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'crf'           => $data['crf'],
            'gender'        => $data['gender'],
            'birthDate'     => Carbon::createFromFormat('d/m/Y', $data['birthDate'])->format('Y-m-d'),
            'zipCode'       => $data['zipCode'],
            'address'       => $data['address'],
            'state'         => $data['state'],
            'city'          => $data['city'],
            'newsletter'    => $data['newsletter'],
            'token'         => md5($data['type'].$data['email']),
            'type'          => $data['type'],
            'password'      => md5($data['password']),
        ]);
    }
}
