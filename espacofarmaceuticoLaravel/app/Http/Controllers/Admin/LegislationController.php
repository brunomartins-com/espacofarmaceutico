<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Legislation;
use App\LegislationCategories;
use App\Texts;

class LegislationController extends Controller
{
    public $textsId;

    public function __construct(){
        $this->textsId = 3;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('legislation')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de Legislações.']);
        }

        $legislation = Legislation::orderBy('sortorder', 'ASC')->get();

        return view('admin.legislation.index')->with(compact('legislation'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('legislation', 'add')) {
            return redirect(route('legislation'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $categories = ['' => 'Escolher...'];
        $categoriesConsult = LegislationCategories::orderBy('legislationCategoriesName', 'ASC')->get();
        foreach ($categoriesConsult as $category) {
            $categories[$category['legislationCategoriesId']] = $category['legislationCategoriesName'];
        }

        return view('admin.legislation.add')->with(compact('categories'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('legislation', 'add')) {
            return redirect(route('legislation'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'legislationCategoriesId'   => 'required',
            'title'                     => 'required|max:45',
            'text'                      => 'required'
        ],
        [
            'legislationCategoriesId.required'  => 'Escolha a categoria',
            'title.required'                    => 'Informe o título',
            'title.max'                         => 'O título não pode passar de :max caracteres',
            'text.required'                     => 'Informe o texto'
        ]);

        $lastSortorder = 0;
        $last = Legislation::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $text = new Legislation();
        $text->legislationCategoriesId  = $request->legislationCategoriesId;
        $text->title                    = $request->title;
        $text->text                     = $request->text;
        $text->sortorder                = $lastSortorder+1;

        $text->save();

        $success = "Legislação adicionada com sucesso.";

        return redirect(route('legislation'))->with(compact('success'));

    }

    public function getEdit($legislationId)
    {
        if (! ACL::hasPermission('legislation', 'edit')) {
            return redirect(route('legislation'))->withErrors(['Você não tem permissão para editar.']);
        }

        $categories = ['' => 'Escolher...'];
        $categoriesConsult = LegislationCategories::orderBy('legislationCategoriesName', 'ASC')->get();
        foreach ($categoriesConsult as $category) {
            $categories[$category['legislationCategoriesId']] = $category['legislationCategoriesName'];
        }

        $legislation = Legislation::where('legislationId', '=', $legislationId)->first();

        return view('admin.legislation.edit')->with(compact('legislation', 'categories'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('legislation', 'edit')) {
            return redirect(route('legislation'))->withErrors(['Você não tem permissão para editar.']);
        }

        $this->validate($request, [
            'legislationCategoriesId'   => 'required',
            'title'                     => 'required|max:45',
            'text'                      => 'required'
        ],
        [
            'legislationCategoriesId.required'  => 'Escolha a categoria',
            'title.required'                    => 'Informe o título',
            'title.max'                         => 'O título não pode passar de :max caracteres',
            'text.required'                     => 'Informe o texto'
        ]);

        $text = Legislation::find($request->legislationId);
        $text->legislationCategoriesId  = $request->legislationCategoriesId;
        $text->title                    = $request->title;
        $text->text                     = $request->text;

        $text->save();

        $success = "Legislação editada com sucesso";

        return redirect(route('legislation'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('legislation', 'edit')) {
            return redirect(route('legislation'))->withErrors(['Você não tem permissão para editar a ordem das legislações.']);
        }

        $items = Legislation::orderBy('sortorder', 'ASC')
            ->addSelect('legislationId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.legislation.order')->with(compact('items'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('legislation', 'delete')) {
            return redirect(route('legislation'))->withErrors(['Você não tem permissão para deletar.']);
        }

        Legislation::find($request->get('legislationId'))->delete();

        $success = "Legislação excluída com sucesso.";

        return redirect(route('legislation'))->with(compact('success'));
    }

    public function getText()
    {
        if (! ACL::hasPermission('legislation') and ! ACL::hasPermission('legislation', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar as Legislação.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();

        return view('admin.legislation.text')->with(compact('texts'));
    }

    public function putTextUpdate(Request $request)
    {
        if (! ACL::hasPermission('legislation', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar a Legislação.']);
        }

        $this->validate($request, [
            'text' => 'required'
        ],
        [
            'text.required' => 'Informe o texto'
        ]);

        $text = Texts::find($this->textsId);
        $text->text = $request->text;
        $text->save();

        $success = "Texto editado com sucesso!";

        return redirect(route('legislationText'))->with(compact('success'));
    }
}