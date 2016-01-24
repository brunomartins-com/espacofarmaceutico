<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\WorkWithUsVacancies;

class WorkWithUsVacanciesController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('workWithUsVacancies')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página Trabalhe Conosco - Vagas.']);
        }

        $workWithUsVacancies = WorkWithUsVacancies::orderBy('sortorder', 'ASC')->get();

        return view('admin.workWithUsVacancies.index')->with(compact('workWithUsVacancies'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('workWithUsVacancies', 'add')) {
            return redirect(route('workWithUsVacancies'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        return view('admin.workWithUsVacancies.add');
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('workWithUsVacancies', 'add')) {
            return redirect(route('workWithUsVacancies'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'details'       => 'required'
        ],
        [
            'title.required'        => 'Informe o título',
            'title.max'             => 'O título não pode passar de :max caracteres',
            'details.required'      => 'Informe os detalhes'
        ]);

        $lastSortorder = 0;
        $last = WorkWithUsVacancies::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $Vacancies = new WorkWithUsVacancies();
        $Vacancies->title        = $request->title;
        $Vacancies->details      = $request->details;
        $Vacancies->sortorder    = $lastSortorder+1;

        $Vacancies->save();

        $success = "Vaga adicionada com sucesso.";

        return redirect(route('workWithUsVacancies'))->with(compact('success'));

    }

    public function getEdit($workWithUsVacanciesId)
    {
        if (! ACL::hasPermission('workWithUsVacancies', 'edit')) {
            return redirect(route('workWithUsVacancies'))->withErrors(['Você não tem permissão para editar.']);
        }

        $workWithUsVacancy = WorkWithUsVacancies::where('workWithUsVacanciesId', '=', $workWithUsVacanciesId)->first();

        return view('admin.workWithUsVacancies.edit')->with(compact('workWithUsVacancy'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('workWithUsVacancies', 'edit')) {
            return redirect(route('workWithUsVacancies'))->withErrors(['Você não tem permissão para editar.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'details'       => 'required'
        ],
        [
            'title.required'        => 'Informe o título',
            'title.max'             => 'O título não pode passar de :max caracteres',
            'details.required'      => 'Informe os detalhes'
        ]);

        $Vacancies = WorkWithUsVacancies::find($request->workWithUsVacanciesId);
        $Vacancies->title    = $request->title;
        $Vacancies->details  = $request->details;

        $Vacancies->save();

        $success = "Vaga editada com sucesso";

        return redirect(route('workWithUsVacancies'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('workWithUsVacancies', 'edit')) {
            return redirect(route('workWithUsVacancies'))->withErrors(['Você não tem permissão para editar a ordem das vagas.']);
        }

        $items = WorkWithUsVacancies::orderBy('sortorder', 'ASC')
            ->addSelect('workWithUsVacanciesId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.workWithUsVacancies.order')->with(compact('items'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('workWithUsVacancies', 'delete')) {
            return redirect(route('workWithUsVacancies'))->withErrors(['Você não tem permissão para deletar.']);
        }

        WorkWithUsVacancies::find($request->get('workWithUsVacanciesId'))->delete();

        $success = "Vaga excluída com sucesso.";

        return redirect(route('workWithUsVacancies'))->with(compact('success'));
    }
}