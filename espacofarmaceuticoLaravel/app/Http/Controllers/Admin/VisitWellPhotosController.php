<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\VisitWellPhotos;

class VisitWellPhotosController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/visitWell/";
        $this->imageWidth   = 650;
        $this->imageHeight  = 488;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('visitWellPhotos')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página Visit Bem - Fotos.']);
        }

        $visitWellPhotos = VisitWellPhotos::orderBy('date', 'DESC')->get();
        foreach($visitWellPhotos as $photo){
            array_set($photo, 'date', Carbon::createFromFormat('Y-m-d', $photo->date)->format('d/m/Y'));
        }

        return view('admin.visitWellPhotos.index')->with(compact('visitWellPhotos'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('visitWellPhotos', 'add')) {
            return redirect(route('visitWellPhotos'))->withErrors(['Você não pode adicionar fotos.']);
        }

        $imageDetails = [
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        return view('admin.visitWellPhotos.add')->with(compact('imageDetails'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('visitWellPhotos', 'add')) {
            return redirect(route('visitWellPhotos'))->withErrors(['Você não pode adicionar fotos.']);
        }

        $this->validate($request, [
            'date'  => 'required|max:10',
            'title' => 'required|max:100',
            'image' => 'image|mimes:jpeg,gif,png'
        ],
        [
            'date.required'     => 'Informe a data',
            'date.max'          => 'A data não pode passar de :max caracteres',
            'title.required'    => 'Informe o nome da turma ou o título da foto',
            'title.max'         => 'O nome da turma ou título da foto não pode passar de :max caracteres',
            'image.image'       => 'Envie um formato de imagem válida',
            'image.mimes'       => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $visitWellPhotos = new VisitWellPhotos();
        $visitWellPhotos->date  = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $visitWellPhotos->title = $request->title;
        $visitWellPhotos->slug  = str_slug($request->title, '-');

        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        $image = Image::make($request->file('image'));
        if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
            $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
        }
        $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
        $visitWellPhotos->image = $nameImage;

        $visitWellPhotos->save();

        $success = "Foto adicionada com sucesso.";

        return redirect(route('visitWellPhotos'))->with(compact('success'));

    }

    public function getEdit($visitWellPhotosId)
    {
        if (! ACL::hasPermission('visitWellPhotos', 'edit')) {
            return redirect(route('visitWellPhotos'))->withErrors(['Você não pode editar fotos.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $visitWellPhotos = VisitWellPhotos::where('visitWellPhotosId', '=', $visitWellPhotosId)->first();
        array_set($visitWellPhotos, 'date', Carbon::createFromFormat('Y-m-d', $visitWellPhotos->date)->format('d/m/Y'));

        return view('admin.visitWellPhotos.edit')->with(compact('visitWellPhotos', 'imageDetails'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('visitWellPhotos', 'edit')) {
            return redirect(route('visitWellPhotos'))->withErrors(['Você não pode editar fotos.']);
        }

        $this->validate($request, [
            'date'  => 'required|max:10',
            'title' => 'required|max:100',
            'image' => 'image|mimes:jpeg,gif,png'
        ],
        [
            'date.required'     => 'Informe a data',
            'date.max'          => 'A data não pode passar de :max caracteres',
            'title.required'    => 'Informe o nome da turma ou o título da foto',
            'title.max'         => 'O nome da turma ou título da foto não pode passar de :max caracteres',
            'image.image'       => 'Envie um formato de imagem válida',
            'image.mimes'       => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $visitWellPhotos = VisitWellPhotos::find($request->visitWellPhotosId);
        $visitWellPhotos->date  = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $visitWellPhotos->title = $request->title;
        $visitWellPhotos->slug  = str_slug($request->title, '-');

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
            $visitWellPhotos->image = $nameImage;
        }

        $visitWellPhotos->save();

        $success = "Foto editada com sucesso";

        return redirect(route('visitWellPhotos'))->with(compact('success'));

    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('visitWellPhotos', 'delete')) {
            return redirect(route('visitWellPhotos'))->withErrors(['Você não pode deletar fotos.']);
        }

        if ($request->image != "") {
            if (File::exists($this->folder . $request->image)) {
                File::delete($this->folder . $request->image);
            }
        }
        VisitWellPhotos::find($request->get('visitWellPhotosId'))->delete();

        $success = "Foto excluída com sucesso.";

        return redirect(route('visitWellPhotos'))->with(compact('success'));
    }
}