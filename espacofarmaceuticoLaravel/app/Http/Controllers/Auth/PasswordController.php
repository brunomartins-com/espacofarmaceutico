<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Advertising;
use App\Calls;
use App\Pages;

class PasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectPath = 'login';

    protected $subject = 'Concurso Bebê Hipoderme - Recuperação de Senha';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

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
        $advertising = Advertising::orderByRaw("RAND()")->get();
        foreach($advertising as $ad){
            array_add($ad, "image", Advertising::imageVideo($ad->url));
            array_set($ad, "url", Advertising::embedVideo($ad->url, 1));
        }
        $calls = Calls::orderByRaw("RAND()")->limit(2)->get();

        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('website.recoveryPassword')->with(compact('token', 'page', 'websiteSettings', 'pages', 'advertising', 'calls', 'awards'));
    }
}
