<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Concept;

class ConceptController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('concept')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página Conceito.']);
        }

        $concept = Concept::orderBy('sortorder', 'ASC')
            ->addSelect('conceptId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.concept.index')->with(compact('concept'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('concept', 'add')) {
            return redirect(route('concept'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        return view('admin.concept.add');
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('concept', 'add')) {
            return redirect(route('concept'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'text'          => 'required'
        ],
        [
            'title.required'        => 'Informe o título',
            'title.max'             => 'O título não pode passar de :max caracteres',
            'text.required'         => 'Informe o texto'
        ]);

        $lastSortorder = 0;
        $last = Concept::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $text = new Concept();
        $text->title        = $request->title;
        $text->text         = $request->text;
        $text->sortorder    = $lastSortorder+1;

        $text->save();

        $success = "Texto adicionado com sucesso.";

        return redirect(route('concept'))->with(compact('success'));

    }

    public function getEdit($conceptId)
    {
        if (! ACL::hasPermission('concept', 'edit')) {
            return redirect(route('concept'))->withErrors(['Você não tem permissão para editar.']);
        }

        $concept = Concept::where('conceptId', '=', $conceptId)->first();

        return view('admin.concept.edit')->with(compact('concept'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('concept', 'edit')) {
            return redirect(route('concept'))->withErrors(['Você não tem permissão para editar.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'text'          => 'required'
        ],
        [
            'title.required'        => 'Informe o título',
            'title.max'             => 'O título não pode passar de :max caracteres',
            'text.required'         => 'Informe o texto'
        ]);

        $text = Concept::find($request->conceptId);
        $text->title    = $request->title;
        $text->text     = $request->text;

        $text->save();

        $success = "Texto editado com sucesso";

        return redirect(route('concept'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('concept', 'edit')) {
            return redirect(route('concept'))->withErrors(['Você não tem permissão para editar a ordem dos textos.']);
        }

        $items = Concept::orderBy('sortorder', 'ASC')
            ->addSelect('conceptId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.concept.order')->with(compact('items'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('concept', 'delete')) {
            return redirect(route('concept'))->withErrors(['Você não tem permissão para deletar.']);
        }

        Concept::find($request->get('conceptId'))->delete();

        $success = "Texto excluído com sucesso.";

        return redirect(route('concept'))->with(compact('success'));
    }
}