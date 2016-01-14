<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Regulation;

class RegulationController extends Controller
{
    public $regulationId;

    public function __construct(){
        $this->regulationId = 1;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('regulation', 'edit')) {
            return redirect(route('regulation'))->withErrors(['Você não tem permissão para editar o regulamento.']);
        }

        $regulation = Regulation::where('regulationId', '=', $this->regulationId)->first();

        return view('admin.regulation.index')->with(compact('regulation'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('regulation', 'edit')) {
            return redirect(route('regulation'))->withErrors(['Você não tem permissão para editar o regulamento.']);
        }

        $this->validate($request, [
            'text' => 'required'
        ],
        [
            'text.required' => 'Informe o regulamento'
        ]);

        $regulation = Regulation::find($this->regulationId);
        $regulation->text = $request->text;
        $regulation->save();

        $success = "Regulamento editado com sucesso!";

        return redirect(route('regulation'))->with(compact('success'));
    }
}