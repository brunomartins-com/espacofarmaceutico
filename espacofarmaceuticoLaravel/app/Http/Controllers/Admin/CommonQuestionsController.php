<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\CommonQuestions;

class CommonQuestionsController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('commonQuestions')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página Perguntas Frequentes.']);
        }

        $commonQuestions = CommonQuestions::orderBy('sortorder', 'ASC')
            ->addSelect('commonQuestionsId')
            ->addSelect('question')
            ->addSelect('sortorder')
            ->get();

        return view('admin.commonQuestions.index')->with(compact('commonQuestions'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('commonQuestions', 'add')) {
            return redirect(route('commonQuestions'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        return view('admin.commonQuestions.add');
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('commonQuestions', 'add')) {
            return redirect(route('commonQuestions'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'question'  => 'required|max:100',
            'answer'    => 'required'
        ],
        [
            'question.required' => 'Informe a pergunta',
            'question.max'      => 'A pergunta não pode passar de :max caracteres',
            'answer.required'   => 'Informe a resposta'
        ]);

        $lastSortorder = 0;
        $last = CommonQuestions::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $text = new CommonQuestions();
        $text->question     = $request->question;
        $text->answer       = $request->answer;
        $text->sortorder    = $lastSortorder+1;

        $text->save();

        $success = "Pergunta adicionada com sucesso.";

        return redirect(route('commonQuestions'))->with(compact('success'));

    }

    public function getEdit($commonQuestionsId)
    {
        if (! ACL::hasPermission('commonQuestions', 'edit')) {
            return redirect(route('commonQuestions'))->withErrors(['Você não tem permissão para editar.']);
        }

        $commonQuestions = CommonQuestions::where('commonQuestionsId', '=', $commonQuestionsId)->first();

        return view('admin.commonQuestions.edit')->with(compact('commonQuestions'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('commonQuestions', 'edit')) {
            return redirect(route('commonQuestions'))->withErrors(['Você não tem permissão para editar.']);
        }

        $this->validate($request, [
            'question'  => 'required|max:100',
            'answer'    => 'required'
        ],
        [
            'question.required' => 'Informe a pergunta',
            'question.max'      => 'A pergunta não pode passar de :max caracteres',
            'answer.required'   => 'Informe a resposta'
        ]);

        $text = CommonQuestions::find($request->commonQuestionsId);
        $text->question = $request->question;
        $text->answer   = $request->answer;

        $text->save();

        $success = "Pergunta editada com sucesso";

        return redirect(route('commonQuestions'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('commonQuestions', 'edit')) {
            return redirect(route('commonQuestions'))->withErrors(['Você não tem permissão para editar a ordem dos textos.']);
        }

        $items = CommonQuestions::orderBy('sortorder', 'ASC')
            ->addSelect('commonQuestionsId')
            ->addSelect('question')
            ->addSelect('sortorder')
            ->get();

        return view('admin.commonQuestions.order')->with(compact('items'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('commonQuestions', 'delete')) {
            return redirect(route('commonQuestions'))->withErrors(['Você não tem permissão para deletar.']);
        }

        CommonQuestions::find($request->get('commonQuestionsId'))->delete();

        $success = "Pergunta excluída com sucesso.";

        return redirect(route('commonQuestions'))->with(compact('success'));
    }
}