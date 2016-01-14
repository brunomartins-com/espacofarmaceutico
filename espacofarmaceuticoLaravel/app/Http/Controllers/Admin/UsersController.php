<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\PagesAdmin;
use App\Permissions;

class UsersController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('users')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para acessar a página de usuários.']);
        }

        $users = User::where('email', '!=', 'hello@brunomartins.com')
            ->where('type', '=', 0)
            ->orderBy('name', 'ASC')
            ->addSelect('id')
            ->addSelect('name')
            ->addSelect('email')
            ->get();

        return view('admin.users.index')->with(compact('users'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('users', 'add')) {
            return redirect(route('users'))->withErrors(['Você não tem permissão para adicionar um novo usuário.']);
        }

        return view('admin.users.add');
    }

    public function postAdd(Request $request)
    {
        if (!ACL::hasPermission('users', 'add')) {
            return redirect(route('users'))->withErrors(['Você não tem permissão para adicionar um novo usuário.']);
        }

        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|email|max:255|unique:users,email,NULL,id,type,0',
            'password' => 'required|confirmed|min:6|max:12',
        ],
        [
            'name.required'             => 'O nome do responsável é obrigatório',
            'name.max'                  => 'O nome do responsável não deve ser maior que :max caracteres',
            'email.required'            => 'O e-mail é obrigatório',
            'email.email'               => 'O e-mail é inválido.',
            'email.unique'              => 'O e-mail já está cadastrado.',
            'password.required'         => 'Informe uma senha',
            'password.min'              => 'A senha não deve ser menor que :min caracteres',
            'password.max'              => 'A senha não deve ser maior que :max caracteres',
            'password.confirmed'        => 'As senhas não conferem'
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = bcrypt($request->password);
        $user->type     = $request->type;
        $user->active   = $request->active;

        $user->save();

        $success = "Usuário cadastrado com sucesso.";

        return redirect(route('usersPermissions', $user->id))->with(compact('success'));
    }

    public function getEdit($userId)
    {
        if (! ACL::hasPermission('users', 'edit')) {
            return redirect(route('users'))->withErrors(['Você não tem permissão para editar usuários.']);
        }

        $user = User::where('id', '=', $userId)->first();

        return view('admin.users.edit')->with(compact('user'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('users', 'edit')) {
            return redirect(route('users'))->withErrors(['Você não tem permissão para editar usuários.']);
        }

        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|email|max:255',
            'password' => 'confirmed|min:6|max:12',
        ],
        [
            'name.required'             => 'O nome do usuário é obrigatório',
            'name.max'                  => 'O nome do usuário não deve ser maior que :max caracteres',
            'email.required'            => 'O e-mail é obrigatório',
            'email.email'               => 'O e-mail é inválido.',
            'password.min'              => 'A senha não deve ser menor que :min caracteres',
            'password.max'              => 'A senha não deve ser maior que :max caracteres',
            'password.confirmed'        => 'As senhas não conferem'
        ]);

        $consultEmail = User::where('type', '=', 0)->where('email', '=', $request->email)->where('id', '!=', $request->userId)->count();
        if($consultEmail > 0){
            $error = "Este e-mail já está sendo usado por outro usuário!";

            return redirect(route('users'))->withErrors(compact('error'));
        }

        $user = User::find($request->userId);
        $user->name     = $request->name;
        $user->email    = $request->email;
        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }
        $user->save();

        $success = "Usuário editado com sucesso!";

        return redirect(route('users'))->with(compact('success'));
    }

    public function getPermissions($userId)
    {
        if (! ACL::hasPermission('users', 'edit') || ! ACL::hasPermission('users', 'add') || ! ACL::hasPermission('users', 'delete')) {
            return redirect(route('users'))->withErrors(['Você não tem permissão para dar permissões aos usuários.']);
        }

        $pagesAdmin = PagesAdmin::permissionsByUser($userId);

        $user = User::where('id', '=', $userId)->first();

        return view('admin.users.permissions')->with(compact('pagesAdmin', 'user'));
    }

    public function postPermissions(Request $request)
    {
        //DELETE ALL
        Permissions::deletePermissionByUser($request->userId);
        //RECORD AGAIN
        $this->savePermissionsForUser($request->userId, $request->all());

        $success = "Permissões alteradas com sucesso!";

        return redirect(route('users'))->with(compact('success'));
    }

    private function savePermissionsForUser($userId, Array $data)
    {
        $pages = [];

        foreach ($data as $k => $value) {
            $page = explode('@', $k);
            if (! isset($page[1])) {
                continue;
            }
            $pages[$page[0]][$page[1]] = $value;
        }

        foreach ($pages as $pageId => $values) {
            Permissions::create([
                'usersId'       => $userId,
                'pageAdminId'   => $pageId,
                'access'        => (isset($values['access']))? $values['access'] : 0,
                'add'           => (isset($values['add']))? $values['add'] : 0,
                'edit'          => (isset($values['edit']))? $values['edit'] : 0,
                'delete'        => (isset($values['delete']))? $values['delete'] : 0
            ]);
        }
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('users', 'delete')) {
            return redirect(route('users'))->withErrors(['Você não tem permissão para deletar usuários.']);
        }

        Permissions::deletePermissionByUser($request->get('userId'));
        User::find($request->get('userId'))->delete();

        $success = "Usuário excluído com sucesso.";

        return redirect(route('users'))->with(compact('success'));
    }
}