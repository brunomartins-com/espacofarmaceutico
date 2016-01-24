<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\DigitalCatalogs;

class DigitalCatalogsController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('digitalCatalogs')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de Catálogos Digitais.']);
        }

        $digitalCatalogs = DigitalCatalogs::orderBy('sortorder', 'ASC')
            ->addSelect('digitalCatalogsId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.digitalCatalogs.index')->with(compact('digitalCatalogs'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('digitalCatalogs', 'add')) {
            return redirect(route('digitalCatalogs'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        return view('admin.digitalCatalogs.add');
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('digitalCatalogs', 'add')) {
            return redirect(route('digitalCatalogs'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'title' => 'required|max:45',
            'embed' => 'required'
        ],
        [
            'title.required'    => 'Informe o título',
            'title.max'         => 'O título não pode passar de :max caracteres',
            'embed.required'    => 'Informe o código de compartilhamento do catálogo'
        ]);

        $lastSortorder = 0;
        $last = DigitalCatalogs::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $text = new DigitalCatalogs();
        $text->title        = $request->title;
        $text->embed        = $request->embed;
        $text->sortorder    = $lastSortorder+1;

        $text->save();

        $success = "Catálogo adicionado com sucesso.";

        return redirect(route('digitalCatalogs'))->with(compact('success'));

    }

    public function getEdit($digitalCatalogsId)
    {
        if (! ACL::hasPermission('digitalCatalogs', 'edit')) {
            return redirect(route('digitalCatalogs'))->withErrors(['Você não tem permissão para editar.']);
        }

        $digitalCatalogs = DigitalCatalogs::where('digitalCatalogsId', '=', $digitalCatalogsId)->first();

        return view('admin.digitalCatalogs.edit')->with(compact('digitalCatalogs'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('digitalCatalogs', 'edit')) {
            return redirect(route('digitalCatalogs'))->withErrors(['Você não tem permissão para editar.']);
        }

        $this->validate($request, [
            'title' => 'required|max:45',
            'embed' => 'required'
        ],
        [
            'title.required'    => 'Informe o título',
            'title.max'         => 'O título não pode passar de :max caracteres',
            'embed.required'    => 'Informe o código de compartilhamento do catálogo'
        ]);

        $text = DigitalCatalogs::find($request->digitalCatalogsId);
        $text->title    = $request->title;
        $text->embed    = $request->embed;

        $text->save();

        $success = "Catálogo editado com sucesso";

        return redirect(route('digitalCatalogs'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('digitalCatalogs', 'edit')) {
            return redirect(route('digitalCatalogs'))->withErrors(['Você não tem permissão para editar a ordem dos catálogos.']);
        }

        $items = DigitalCatalogs::orderBy('sortorder', 'ASC')
            ->addSelect('digitalCatalogsId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.digitalCatalogs.order')->with(compact('items'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('digitalCatalogs', 'delete')) {
            return redirect(route('digitalCatalogs'))->withErrors(['Você não tem permissão para deletar.']);
        }

        DigitalCatalogs::find($request->get('digitalCatalogsId'))->delete();

        $success = "Catálogo excluído com sucesso.";

        return redirect(route('digitalCatalogs'))->with(compact('success'));
    }
}