<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;

class TheTeutoController extends Controller
{
    public $textsId;

    public function __construct(){
        $this->textsId = 1;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('theTeuto', 'edit')) {
            return redirect(route('theTeuto'))->withErrors(['Você não tem permissão para editar o texto sobre O Teuto.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();

        return view('admin.theTeuto.index')->with(compact('texts'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('theTeuto', 'edit')) {
            return redirect(route('theTeuto'))->withErrors(['Você não tem permissão para editar o texto sobre O Teuto.']);
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

        return redirect(route('theTeuto'))->with(compact('success'));
    }
}