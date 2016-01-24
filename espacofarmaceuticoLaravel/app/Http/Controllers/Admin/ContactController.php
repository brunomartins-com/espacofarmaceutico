<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;
use App\EmailsContact;

class ContactController extends Controller
{
    public $textsId;

    public function __construct(){
        $this->textsId = 2;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('contact') and ! ACL::hasPermission('contact', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar o contato.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();
        $emails = EmailsContact::find(1);

        return view('admin.contact.index')->with(compact('texts', 'emails'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('contact', 'edit')) {
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

        $emails = EmailsContact::find(1);
        $emails->emails = $request->emails;
        $emails->save();

        $success = "Dados editados com sucesso!";

        return redirect(route('contact'))->with(compact('success'));
    }
}