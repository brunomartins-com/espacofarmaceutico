<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;

class RegionalCouncilsController extends Controller
{
    public $textsId;

    public function __construct(){
        $this->textsId = 10;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('regionalCouncils') and ! ACL::hasPermission('regionalCouncils', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar os Conselhos Regionais.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();

        return view('admin.regionalCouncils.index')->with(compact('texts'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('regionalCouncils', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar os Conselhos Regionais.']);
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

        return redirect(route('regionalCouncils'))->with(compact('success'));
    }
}