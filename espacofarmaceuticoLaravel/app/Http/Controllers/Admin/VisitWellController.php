<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;
use App\EmailsVisitWell;

class VisitWellController extends Controller
{
    public $textsId;
    public $scheduleId;

    public function __construct(){
        $this->textsId = 20;
        $this->scheduleId = 21;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('visitWell') and ! ACL::hasPermission('contact', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar o contato.']);
        }

        $texts = Texts::find($this->textsId);
        $scheduleText = Texts::find($this->scheduleId);
        $emails = EmailsVisitWell::find(1);

        return view('admin.visitWell.index')->with(compact('texts', 'scheduleText', 'emails'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('contact', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar o contato.']);
        }

        $this->validate($request, [
            'text'          => 'required',
            'scheduleText'  => 'required',
            'emails'        => 'required'
        ],
        [
            'text.required'         => 'Informe o texto',
            'scheduleText.required' => 'Informe o texto',
            'emails.required'       => 'Informe os e-mails'
        ]);

        $text = Texts::find($this->textsId);
        $text->text = $request->text;
        $text->save();

        $scheduleText = Texts::find($this->scheduleId);
        $scheduleText->text = $request->scheduleText;
        $scheduleText->save();

        $emails = EmailsVisitWell::find(1);
        $emails->emails = $request->emails;
        $emails->save();

        $success = "Dados editados com sucesso!";

        return redirect(route('visitWell'))->with(compact('success'));
    }
}