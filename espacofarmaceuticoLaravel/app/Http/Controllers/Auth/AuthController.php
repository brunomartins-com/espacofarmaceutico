<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
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
            'babyName' => 'required|max:100',
            'babyBirthdate' => 'required',
            'babyGender' => 'required',
            'birthCertificateFile' => 'required|mimes:jpeg,gif,bmp,png',
            'name' => 'required|max:100',
            'rg' => 'required',
            'cpf' => 'required|min:14|max:14|unique:users',
            'address' => 'required|max:150',
            'gender' => 'required',
            'number' => 'required|max:10',
            'complement' => 'max:50',
            'district' => 'required|max:50',
            'state' => 'required|max:2',
            'city' => 'required',
            'phone' => 'max:14',
            'mobile' => 'max:15',
            'email' => 'required|email|max:255|unique:users,email,NULL,id,type,1',
            'terms' => 'required',
            'password' => 'required|confirmed|min:6|max:12',
        ],
        [
            'babyName.required'         => 'O nome do bebê é obrigatório',
            'babyName.max'              => 'O nome do bebê não deve ser maior que :max caracteres',
            'babyBirthdate.required'    => 'A data de nascimento do bebê é obrigatória',
            'babyGender.required'       => 'O sexo do bebê é obrigatório',
            'birthCertificateFile.required' => 'Envie a certidão de nascimento do bebê',
            'birthCertificateDile.mimes'    => 'O formato da imagem da certidão de nascimento é inválido',
            'name.required'             => 'O nome do responsável é obrigatório',
            'name.max'                  => 'O nome do responsável não deve ser maior que :max caracteres',
            'rg.required'               => 'O RG é obrigatório',
            'cpf.required'              => 'O CPF é obrigatório',
            'cpf.min'                   => 'O CPF deve ter exatamente :min caracteres',
            'cpf.max'                   => 'O CPF deve ter exatamente :max caracteres',
            'cpf.unique'                => 'O CPF já está cadastrado.',
            'address.required'          => 'O endereço é obrigatório',
            'address.max'               => 'O endereço não deve ser maior que :max caracteres',
            'gender.required'           => 'Informar o sexo do responsável é obrigatório',
            'number.required'           => 'O número é obrigatório',
            'complement.max'            => 'O complemento não pode ser maior que :max caracteres',
            'district.required'         => 'O bairro é obrigatório',
            'district.max'              => 'O bairro não pode ser maior que :max caracteres',
            'state.required'            => 'É preciso escolhaer um Estado',
            'state.max'                 => 'UF do Estado inválida',
            'city.required'             => 'É preciso escolher uma cidade',
            'phone.max'                 => 'O telefone fixo não pode passar de :max caracteres',
            'mobile.max'                => 'O celular não pode passar de :max caracteres',
            'email.required'            => 'O e-mail é obrigatório',
            'email.email'               => 'O e-mail é inválido.',
            'email.unique'              => 'O e-mail já está cadastrado.',
            'terms'                     => 'Você precisa aceitar os termos',
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
            'babyName' => $data['babyName'],
            'babyBirthdate' => Carbon::createFromFormat('d/m/Y', $data['babyBirthdate'])->format('Y-m-d'),
            'babyGender' => $data['babyGender'],
            'birthCertificate' => $data['birthCertificate'],
            'name' => $data['name'],
            'rg' => $data['rg'],
            'cpf' => $data['cpf'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'number' => $data['number'],
            'complement' => $data['complement'],
            'district' => $data['district'],
            'state' => $data['state'],
            'city' => $data['city'],
            'phone' => $data['phone'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'token' => md5($data['type'].$data['email']),
            'type' => $data['type'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
