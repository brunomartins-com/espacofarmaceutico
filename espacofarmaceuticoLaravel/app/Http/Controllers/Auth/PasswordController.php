<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

use App\User;
use App\Pages;
use Password;
use App\PasswordResets;

class PasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectPath = 'login';

    protected $subject = 'Espaço Farmacêutico - Recuperação de Senha';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function resetPassword($user, $password)
    {
        $user->password = md5($password);

        $user->save();
    }

    public function getEmail()
    {
        return view('auth.password');
    }

    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('auth.reset')->with('token', $token);
    }

    public function getResetWebsite($token = null)
    {
        $page = 'recuperar-senha';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $user = PasswordResets::where('token', '=', $token)->first();

        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('website.profile.recoveryPassword')->with(compact('token', 'page', 'websiteSettings', 'pages', 'user'));
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->getSendResetLinkEmailSuccessResponse($response);

            case Password::INVALID_USER:
            default:
                return $this->getSendResetLinkEmailFailureResponse($response);
        }
    }

    /**
     * Reset the given website user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postResetWebsite(Request $request)
    {
        $this->validate($request, [
            'token'     => 'required',
            'email'     => 'required|email',
            'password'  => 'required|confirmed|min:6|max:16',
        ],
        [
            'token.required'        => 'O token é obrigatório',
            'email.required'        => 'Informe seu e-mail',
            'email.email'           => 'Informe um e-mail válido',
            'password.required'     => 'Informe uma senha',
            'password.min'          => 'A senha não deve ser menor que :min caracteres',
            'password.max'          => 'A senha não deve ser maior que :max caracteres',
            'password.confirmed'    => 'As senhas não conferem'
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect('/')->with('status', trans($response))->with('success', 'Senha alterada com sucesso.');

            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }
}
