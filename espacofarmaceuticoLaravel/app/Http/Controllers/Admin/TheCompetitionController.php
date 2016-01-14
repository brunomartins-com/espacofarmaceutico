<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use League\Flysystem\Filesystem;

use App\TheCompetition;

class TheCompetitionController extends Controller
{
    public $regulationId;
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->theCompetitionId = 1;
        $this->folder       = "assets/images/_upload/o-concurso/";
        $this->imageWidth   = 352;
        $this->imageHeight  = 343;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('theCompetition', 'edit')) {
            return redirect(route('theCompetition'))->withErrors(['Você não tem permissão para editar o concurso.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $theCompetition = TheCompetition::where('theCompetitionId', '=', $this->theCompetitionId)->first();

        return view('admin.theCompetition.index')->with(compact('theCompetition', 'imageDetails'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('theCompetition', 'edit')) {
            return redirect(route('theCompetition'))->withErrors(['Você não tem permissão para editar o concurso.']);
        }

        $this->validate($request, [
            'text' => 'required'
        ],
        [
            'text.required' => 'Informe o texto sobre o concurso'
        ]);

        $theCompetition = TheCompetition::find($this->theCompetitionId);
        $theCompetition->text = $request->text;

        if ($request->image) {
            //DELETE OLD IMAGE
            if($request->currentImage != ""){
                if(File::exists($this->folder.$request->currentImage)){
                    File::delete($this->folder.$request->currentImage);
                }
            }
            $extension = $request->image->getClientOriginalExtension();
            $nameImage = Carbon::now()->format('YmdHis').".".$extension;
            Image::make($request->file('image'))->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);

            $theCompetition->image = $nameImage;
        }

        $theCompetition->save();

        $success = "Texto editado com sucesso!";

        return redirect(route('theCompetition'))->with(compact('success'));
    }
}