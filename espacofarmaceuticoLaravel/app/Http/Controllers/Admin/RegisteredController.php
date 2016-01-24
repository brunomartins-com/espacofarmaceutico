<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\User;

class RegisteredController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('registered')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para acessar a página de Cadastrados.']);
        }

        $users = User::where('type', '=', 1)
            ->orderBy('id', 'DESC')
            ->addSelect('id')
            ->addSelect('name')
            ->addSelect('email')
            ->addSelect('crf')
            ->addSelect('city')
            ->addSelect('state')
            ->addSelect('active')
            ->get();

        return view('admin.registered.index')->with(compact('users'));
    }

    public function getView($usersId)
    {
        if (! ACL::hasPermission('registered')) {
            return redirect(route('registered'))->withErrors(['Você não tem permissão para visualizar dados dos cadastrados.']);
        }

        $user = User::where('id', '=', $usersId)->first();
        array_set($user, 'birthDate', Carbon::createFromFormat('Y-m-d', $user->birthDate)->format('d/m/Y'));

        return view('admin.registered.view')->with(compact('user'));
    }

    public function putStatus(Request $request)
    {
        if (! ACL::hasPermission('registered', 'edit')) {
            return redirect(route('registered'))->withErrors(['Você não tem permissão para editar o status dos cadastrados.']);
        }
        $user = User::find($request->userId);
        $user->active = $request->active;
        $user->save();

        $success = "Status do cadastrado editado com sucesso!";

        return redirect(route('registered'))->with(compact('success'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('registered', 'delete')) {
            return redirect(route('registered'))->withErrors(['Você não tem permissão para deletar cadastrados.']);
        }

        User::find($request->get('userId'))->delete();

        $success = "Cadastrado excluído com sucesso.";

        return redirect(route('registered'))->with(compact('success'));
    }
}