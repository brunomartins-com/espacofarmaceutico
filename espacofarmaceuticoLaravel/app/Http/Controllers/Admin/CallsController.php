<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\Calls;

class CallsController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/calls/";
        $this->imageWidth   = 353;
        $this->imageHeight  = 200;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('calls')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de chamadas.']);
        }

        $imageDetails = ['folder' => $this->folder];

        $calls = Calls::orderBy('callsId', 'DESC')->get();

        return view('admin.calls.index')->with(compact('calls', 'imageDetails'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('calls', 'add')) {
            return redirect(route('calls'))->withErrors(['Você não pode adicionar chamadas.']);
        }

        $imageDetails = [
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        return view('admin.calls.add')->with(compact('imageDetails'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('calls', 'add')) {
            return redirect(route('calls'))->withErrors(['Você não pode adicionar chamadas.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'image'         => 'required|image|mimes:jpeg,gif,png'
        ],
        [
            'title.required'=> 'Informe o título da chamada',
            'title.max'     => 'O título da chamada não pode passar de :max caracteres',
            'image.required'=> 'Envie uma imagem para a chamada',
            'image.image'   => 'Envie um formato de imagem válida',
            'image.mimes'   => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $calls = new Calls();
        $calls->title       = $request->title;
        if(!empty($request->url)){
            $calls->url = $request->url;
            if(!empty($request->target)){
                $calls->target = $request->target;
            }else{
                $calls->target = "_self";
            }
        }else{
            $calls->url = "";
            $calls->target = "";
        }
        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        $image = Image::make($request->file('image'));
        if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
            $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
        }
        $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
        $calls->image = $nameImage;

        $calls->save();

        $success = "Chamada adicionada com sucesso.";

        return redirect(route('calls'))->with(compact('success'));
    }

    public function getEdit($callsId)
    {
        if (! ACL::hasPermission('calls', 'edit')) {
            return redirect(route('calls'))->withErrors(['Você não pode editar chamadas.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $call = Calls::where('callsId', '=', $callsId)->first();

        return view('admin.calls.edit')->with(compact('call', 'imageDetails'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('calls', 'edit')) {
            return redirect(route('calls'))->withErrors(['Você não pode editar chamadas.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'image'         => 'image|mimes:jpeg,gif,png'
        ],
        [
            'title.required'=> 'Informe o título da chamada',
            'title.max'     => 'O título da chamada não pode passar de :max caracteres',
            'image.image'   => 'Envie um formato de imagem válida',
            'image.mimes'   => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $calls = Calls::find($request->callsId);
        $calls->title       = $request->title;
        if(!empty($request->url)){
            $calls->url = $request->url;
            if(!empty($request->target)){
                $calls->target = $request->target;
            }else{
                $calls->target = "_self";
            }
        }else{
            $calls->url = "";
            $calls->target = "";
        }

        if ($request->image) {
            //DELETE OLD IMAGE
            if($request->currentImage != ""){
                if(File::exists($this->folder.$request->currentImage)){
                    File::delete($this->folder.$request->currentImage);
                }
            }
            //IMAGE
            $extension = $request->image->getClientOriginalExtension();
            $nameImage = Carbon::now()->format('YmdHis').".".$extension;
            $image = Image::make($request->file('image'));
            if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
                $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
            }
            $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
            $calls->image = $nameImage;
        }

        $calls->save();

        $success = "Chamada editada com sucesso";

        return redirect(route('calls'))->with(compact('success'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('calls', 'delete')) {
            return redirect(route('calls'))->withErrors(['Você não pode deletar chamadas.']);
        }

        if ($request->image != "") {
            if (File::exists($this->folder . $request->image)) {
                File::delete($this->folder . $request->image);
            }
        }
        Calls::find($request->get('callsId'))->delete();

        $success = "Chamada excluída com sucesso.";

        return redirect(route('calls'))->with(compact('success'));
    }
}