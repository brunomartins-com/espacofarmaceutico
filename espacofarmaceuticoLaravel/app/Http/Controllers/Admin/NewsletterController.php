<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SoapBox\Formatter\Formatter;

use App\Newsletter;

class NewsletterController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('newsletter')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para acessar a página de newsletter.']);
        }

        $emails = Newsletter::orderBy('newsletterId', 'DESC')->get();

        return view('admin.newsletter.index')->with(compact('emails'));
    }

    public function getExport()
    {
        if (! ACL::hasPermission('newsletter')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para exportar os e-mails da newsletter.']);
        }

        $emailsArray = Newsletter::orderBy('newsletterId', 'DESC')->addSelect('email')->get()->toArray();

        $formatter = Formatter::make($emailsArray, Formatter::ARR);

        $csv = $formatter->toCsv();

        header('Content-Disposition: attachment; filename="Emails_BebeHipoderme2015.csv"');
        header("Cache-control: private");
        header("Content-type: application/force-download");
        header("Content-transfer-encoding: binary\n");

        echo $csv;

        exit;
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('newsletter', 'delete')) {
            return redirect(route('newsletter'))->withErrors(['Você não tem permissão para deletar e-mails.']);
        }

        Newsletter::find($request->get('newsletterId'))->delete();

        $success = "E-mail excluído com sucesso.";

        return redirect(route('newsletter'))->with(compact('success'));
    }
}