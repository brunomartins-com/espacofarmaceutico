<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;
use App\EmailsFarmacovigilance;

class FarmacovigilanceController extends Controller
{
    public $textsId;

    public function __construct(){
        $this->textsId = 6;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('farmacovigilance') and ! ACL::hasPermission('farmacovigilance', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar a farmacovigilância.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();
        $emails = EmailsFarmacovigilance::find(1);

        return view('admin.farmacovigilance.index')->with(compact('texts', 'emails'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('farmacovigilance', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar o contato.']);
        }

        $this->validate($request, [
            'text'   => 'required',
            'emails' => 'required'
        ],
        [
            'text.required'   => 'Informe o texto',
            'emails.required' => 'Informe os e-mails'
        ]);

        $text = Texts::find($this->textsId);
        $text->text = $request->text;
        $text->save();

        $emails = EmailsFarmacovigilance::find(1);
        $emails->emails = $request->emails;
        $emails->save();

        $success = "Dados editados com sucesso!";

        return redirect(route('farmacovigilance'))->with(compact('success'));
    }
}