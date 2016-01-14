<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Advertising;

class AdvertisingController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('advertising')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de propagandas.']);
        }

        $advertising = Advertising::orderBy('advertisingId', 'DESC')->get();
        foreach($advertising as $ad){
            array_add($ad, "image", Advertising::imageVideo($ad->url));
        }

        return view('admin.advertising.index')->with(compact('advertising'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('advertising', 'add')) {
            return redirect(route('advertising'))->withErrors(['Você não pode adicionar propagandas.']);
        }

        return view('admin.advertising.add');
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('advertising', 'add')) {
            return redirect(route('advertising'))->withErrors(['Você não pode adicionar propagandas.']);
        }

        $this->validate($request, [
            'title' => 'required|max:100',
            'url'   => 'required',
        ],
        [
            'title.required'=> 'Informe o título da propaganda',
            'title.max'     => 'O título da propaganda não pode passar de :max caracteres',
            'url.required'  => 'Informe o link da propaganda'
        ]);

        $advertising = new Advertising();
        $advertising->title = $request->title;
        $advertising->url   = $request->url;

        $advertising->save();

        $success = "Propaganda adicionada com sucesso.";

        return redirect(route('advertising'))->with(compact('success'));
    }

    public function getEdit($advertisingId)
    {
        if (! ACL::hasPermission('advertising', 'edit')) {
            return redirect(route('advertising'))->withErrors(['Você não pode editar propagandas.']);
        }

        $advertising = Advertising::where('advertisingId', '=', $advertisingId)->first();

        return view('admin.advertising.edit')->with(compact('advertising'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('advertising', 'edit')) {
            return redirect(route('advertising'))->withErrors(['Você não pode editar propagandas.']);
        }

        $this->validate($request, [
            'title' => 'required|max:100',
            'url'   => 'required',
        ],
        [
            'title.required'=> 'Informe o título da propaganda',
            'title.max'     => 'O título da propaganda não pode passar de :max caracteres',
            'url.required'  => 'Informe o link da propaganda'
        ]);

        $advertising = Advertising::find($request->advertisingId);
        $advertising->title = $request->title;
        $advertising->url   = $request->url;

        $advertising->save();

        $success = "Propaganda editada com sucesso";

        return redirect(route('advertising'))->with(compact('success'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('advertising', 'delete')) {
            return redirect(route('advertising'))->withErrors(['Você não pode deletar propagandas.']);
        }

        Advertising::find($request->get('advertisingId'))->delete();

        $success = "Propaganda excluída com sucesso.";

        return redirect(route('advertising'))->with(compact('success'));
    }
}