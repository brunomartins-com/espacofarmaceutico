<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ReducingSpendingOnHealth;

class ReducingSpendingOnHealthController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('reducingSpendingOnHealth')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página Redução de Gastos com a Saúde.']);
        }

        $reducingSpendingOnHealth = ReducingSpendingOnHealth::orderBy('sortorder', 'ASC')
            ->addSelect('reducingSpendingOnHealthId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.reducingSpendingOnHealth.index')->with(compact('reducingSpendingOnHealth'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('reducingSpendingOnHealth', 'add')) {
            return redirect(route('reducingSpendingOnHealth'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        return view('admin.reducingSpendingOnHealth.add');
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('reducingSpendingOnHealth', 'add')) {
            return redirect(route('reducingSpendingOnHealth'))->withErrors(['Você não tem permissão para adicionar.']);
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
        $last = ReducingSpendingOnHealth::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $text = new ReducingSpendingOnHealth();
        $text->title        = $request->title;
        $text->text         = $request->text;
        $text->sortorder    = $lastSortorder+1;

        $text->save();

        $success = "Texto adicionado com sucesso.";

        return redirect(route('reducingSpendingOnHealth'))->with(compact('success'));

    }

    public function getEdit($reducingSpendingOnHealthId)
    {
        if (! ACL::hasPermission('reducingSpendingOnHealth', 'edit')) {
            return redirect(route('reducingSpendingOnHealth'))->withErrors(['Você não tem permissão para editar.']);
        }

        $reducingSpendingOnHealth = ReducingSpendingOnHealth::where('reducingSpendingOnHealthId', '=', $reducingSpendingOnHealthId)->first();

        return view('admin.reducingSpendingOnHealth.edit')->with(compact('reducingSpendingOnHealth'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('reducingSpendingOnHealth', 'edit')) {
            return redirect(route('reducingSpendingOnHealth'))->withErrors(['Você não tem permissão para editar.']);
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

        $text = ReducingSpendingOnHealth::find($request->reducingSpendingOnHealthId);
        $text->title    = $request->title;
        $text->text     = $request->text;

        $text->save();

        $success = "Texto editado com sucesso";

        return redirect(route('reducingSpendingOnHealth'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('reducingSpendingOnHealth', 'edit')) {
            return redirect(route('reducingSpendingOnHealth'))->withErrors(['Você não tem permissão para editar a ordem dos textos.']);
        }

        $items = ReducingSpendingOnHealth::orderBy('sortorder', 'ASC')
            ->addSelect('reducingSpendingOnHealthId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.reducingSpendingOnHealth.order')->with(compact('items'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('reducingSpendingOnHealth', 'delete')) {
            return redirect(route('reducingSpendingOnHealth'))->withErrors(['Você não tem permissão para deletar.']);
        }

        ReducingSpendingOnHealth::find($request->get('reducingSpendingOnHealthId'))->delete();

        $success = "Texto excluído com sucesso.";

        return redirect(route('reducingSpendingOnHealth'))->with(compact('success'));
    }
}