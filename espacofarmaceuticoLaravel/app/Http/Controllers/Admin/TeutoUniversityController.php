<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;

class TeutoUniversityController extends Controller
{
    public $textsId;

    public function __construct(){
        $this->textsId = 4;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('teutoUniversity') and ! ACL::hasPermission('teutoUniversity', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar a Universidade Teuto.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();

        return view('admin.teutoUniversity.index')->with(compact('texts'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('teutoUniversity', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar a Universidade Teuto.']);
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

        return redirect(route('teutoUniversity'))->with(compact('success'));
    }
}