<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Pages;

class PagesController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('pages')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para acessar as páginas.']);
        }

        $pages = Pages::orderBy('pagesId', 'ASC')
            ->addSelect('pagesId')
            ->addSelect('title')
            ->addSelect('description')
            ->get();

        return view('admin.pages.index')->with(compact('pages'));
    }

    public function getEdit($pagesId)
    {
        if (! ACL::hasPermission('pages', 'edit')) {
            return redirect(route('pages'))->withErrors(['Você não tem permissão para editar as páginas.']);
        }

        $page = Pages::where('pagesId', '=', $pagesId)->first();

        return view('admin.pages.edit')->with(compact('page'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('pages', 'edit')) {
            return redirect(route('pages'))->withErrors(['Você não tem permissão para editar as páginas.']);
        }

        $this->validate($request, [
            'description' => 'required|max:200',
            'keywords'    => 'required',
        ],
        [
            'description.required'  => 'Informe a descrição da página',
            'description.max'       => 'A descrição da página não pode ter mais de :max caracteres',
            'keyword.required'      => 'Informe as palavras-chave da página'
        ]);

        $pages = Pages::find($request->pagesId);
        $pages->description = $request->description;
        $pages->keywords    = $request->keywords;

        $pages->save();

        $success = "Página editada com sucesso!";

        return redirect(route('pages'))->with(compact('success'));
    }
}