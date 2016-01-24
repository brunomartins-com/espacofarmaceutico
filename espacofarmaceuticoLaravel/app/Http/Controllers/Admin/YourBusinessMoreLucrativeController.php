<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;

class YourBusinessMoreLucrativeController extends Controller
{
    public $textsId;

    public function __construct(){
        $this->textsId = 18;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('yourBusinessMoreLucrative') and ! ACL::hasPermission('yourBusinessMoreLucrative', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar o texto do Seu Negócio + Lucrativo.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();

        return view('admin.yourBusinessMoreLucrative.index')->with(compact('texts'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('yourBusinessMoreLucrative', 'edit')) {
            return redirect(route('yourBusinessMoreLucrative'))->withErrors(['Você não tem permissão para editar o texto do Seu Negócio + Lucrativo.']);
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

        return redirect(route('yourBusinessMoreLucrative'))->with(compact('success'));
    }
}