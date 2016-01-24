<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Texts;

class TheTeutoController extends Controller
{
    public $textsId;
    public $videoId;

    public function __construct(){
        $this->textsId = 1;
        $this->videoId = 17;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('theTeuto') and ! ACL::hasPermission('theTeuto', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar o texto sobre O Teuto.']);
        }

        $texts = Texts::where('textsId', '=', $this->textsId)->first();
        $video = Texts::where('textsId', '=', $this->videoId)->first();

        return view('admin.theTeuto.index')->with(compact('texts', 'video'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('theTeuto', 'edit')) {
            return redirect(route('theTeuto'))->withErrors(['Você não tem permissão para editar o texto sobre O Teuto.']);
        }

        $this->validate($request, [
            'text'  => 'required',
            'video' => 'required'
        ],
        [
            'text.required'  => 'Informe o texto',
            'video.required' => 'Informe a URL do vídeo'
        ]);

        $text = Texts::find($this->textsId);
        $text->text = $request->text;
        $text->save();

        $video = Texts::find($this->videoId);
        $video->text = $request->video;
        $video->save();

        $success = "Texto editado com sucesso!";

        return redirect(route('theTeuto'))->with(compact('success'));
    }
}