<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;

class BullaInstituteController extends Controller
{
    public $textsId;

    public function __construct(){
        $this->textsId = 19;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('bullaInstitute') and ! ACL::hasPermission('bullaInstitute', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar o Instituto Bulla.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();

        return view('admin.bullaInstitute.index')->with(compact('texts'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('bullaInstitute', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar o Instituto Bulla.']);
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

        return redirect(route('bullaInstitute'))->with(compact('success'));
    }
}