<?php

namespace App\Http\Controllers\Auth;

use App\ACL;
use App\User;
use Illuminate\Http\Request;
use Auth;
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
     * Handle a website login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLoginWebsite(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        array_add($request, 'active', '1');
        $credentials = $this->getCredentials($request);

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        //return $this->sendFailedLoginResponse($request);
        return redirect('/cadastre-se')
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            if(Auth::user()->type == 0){
                redirect('/')->withErrors('Usuário não cadastrado!');
            }else if(Auth::user()->type == 1 and Auth::user()->active == 0){
                return redirect('/')->withErrors('Seu cadastro ainda não está ativo\nPara ativá-lo entre em seu e-mail e siga as instruções.');
            }else {
                return $this->authenticated($request, Auth::user());
            }
        }

        if(Auth::user()->type == 1){
            return redirect('/');
        }else {
            ACL::loadPermissions();

            return redirect()->intended($this->redirectPath());
        }
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        if($request->get('type') == 1){
            $credentials = [
                'email'     => $request->get('email'),
                'password'  => md5($request->get('password')),
                'type'      => $request->get('type')
            ];
        }else {
            $credentials = [
                'email'     => $request->get('email'),
                'password'  => md5($request->get('password')),
                'type'      => $request->get('type'),
                'active'    => 1
            ];
        }
        return $credentials;
        //return $request->only($this->loginUsername(), 'password');
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
