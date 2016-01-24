<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ReliabilityAndQuality;

class ReliabilityAndQualityController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('reliabilityAndQuality')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página Confiabilidade e Qualidade.']);
        }

        $reliabilityAndQuality = ReliabilityAndQuality::orderBy('sortorder', 'ASC')
            ->addSelect('reliabilityAndQualityId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.reliabilityAndQuality.index')->with(compact('reliabilityAndQuality'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('reliabilityAndQuality', 'add')) {
            return redirect(route('reliabilityAndQuality'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        return view('admin.reliabilityAndQuality.add');
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('reliabilityAndQuality', 'add')) {
            return redirect(route('reliabilityAndQuality'))->withErrors(['Você não tem permissão para adicionar.']);
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
        $last = ReliabilityAndQuality::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $text = new ReliabilityAndQuality();
        $text->title        = $request->title;
        $text->text         = $request->text;
        $text->sortorder    = $lastSortorder+1;

        $text->save();

        $success = "Texto adicionado com sucesso.";

        return redirect(route('reliabilityAndQuality'))->with(compact('success'));

    }

    public function getEdit($reliabilityAndQualityId)
    {
        if (! ACL::hasPermission('reliabilityAndQuality', 'edit')) {
            return redirect(route('reliabilityAndQuality'))->withErrors(['Você não tem permissão para editar.']);
        }

        $reliabilityAndQuality = ReliabilityAndQuality::where('reliabilityAndQualityId', '=', $reliabilityAndQualityId)->first();

        return view('admin.reliabilityAndQuality.edit')->with(compact('reliabilityAndQuality'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('reliabilityAndQuality', 'edit')) {
            return redirect(route('reliabilityAndQuality'))->withErrors(['Você não tem permissão para editar.']);
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

        $text = ReliabilityAndQuality::find($request->reliabilityAndQualityId);
        $text->title    = $request->title;
        $text->text     = $request->text;

        $text->save();

        $success = "Texto editado com sucesso";

        return redirect(route('reliabilityAndQuality'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('reliabilityAndQuality', 'edit')) {
            return redirect(route('reliabilityAndQuality'))->withErrors(['Você não tem permissão para editar a ordem dos textos.']);
        }

        $items = ReliabilityAndQuality::orderBy('sortorder', 'ASC')
            ->addSelect('reliabilityAndQualityId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.reliabilityAndQuality.order')->with(compact('items'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('reliabilityAndQuality', 'delete')) {
            return redirect(route('reliabilityAndQuality'))->withErrors(['Você não tem permissão para deletar.']);
        }

        ReliabilityAndQuality::find($request->get('reliabilityAndQualityId'))->delete();

        $success = "Texto excluído com sucesso.";

        return redirect(route('reliabilityAndQuality'))->with(compact('success'));
    }
}