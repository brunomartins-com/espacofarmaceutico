<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;

class ProfileController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('profile', 'edit')) {
            return redirect(route('profile'))->withErrors(['Você não tem permissão para editar seus dados.']);
        }

        $user = User::where('id', '=', \Auth::getUser()->id)->first();

        return view('admin.profile.index')->with(compact('user'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('profile', 'edit')) {
            return redirect(route('profile'))->withErrors(['Você não tem permissão para editar seus dados.']);
        }

        $this->validate($request, [
                'name' => 'required|max:100',
                'email' => 'required|email|max:100',
                'password' => 'confirmed|min:6|max:12',
        ],
        [
            'name.required'             => 'O seu nome é obrigatório',
            'name.max'                  => 'O nome não deve ser maior que :max caracteres',
            'email.required'            => 'O e-mail é obrigatório',
            'email.email'               => 'O e-mail é inválido.',
            'password.min'              => 'A senha não deve ser menor que :min caracteres',
            'password.max'              => 'A senha não deve ser maior que :max caracteres',
            'password.confirmed'        => 'As senhas não conferem'
        ]);

        $consultEmail = User::where('type', '=', 0)->where('email', '=', $request->email)->where('id', '!=', $request->userId)->count();
        if($consultEmail > 0){
            $error = "Este e-mail já está sendo usado por outro usuário!";

            return redirect(route('profile'))->with(compact('error'));
        }
        $user = User::find($request->userId);
        $user->name     = $request->name;
        $user->email    = $request->email;
        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }
        $user->save();

        $success = "Dados editados com sucesso!";

        return redirect(route('profile'))->with(compact('success'));
    }
}