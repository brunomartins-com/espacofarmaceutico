<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\Movies3D;

class Movies3DController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/movies3D/";
        $this->imageWidth   = 600;
        $this->imageHeight  = 175;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('movies3D')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de Vídeos 3D.']);
        }

        $movies3D = Movies3D::orderBy('date', 'DESC')->get();
        foreach($movies3D as $movie){
            array_set($movie, 'date', Carbon::createFromFormat('Y-m-d', $movie->date)->format('d/m/Y'));
        }

        return view('admin.movies3D.index')->with(compact('movies3D'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('movies3D', 'add')) {
            return redirect(route('movies3D'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $imageDetails = [
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        return view('admin.movies3D.add')->with(compact('imageDetails'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('movies3D', 'add')) {
            return redirect(route('movies3D'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'date'          => 'required|max:10',
            'title'         => 'required|max:100',
            'url'           => 'required',
            'image'         => 'required|image|mimes:jpeg,gif,png'
        ],
        [
            'date.required'         => 'Informe a data',
            'date.max'              => 'A data não pode passar de :max caracteres',
            'title.required'        => 'Informe o título',
            'title.max'             => 'O título não pode passar de :max caracteres',
            'url.required'          => 'Informe o endereço do vídeo',
            'image.required'        => 'Envie a imagem do vídeo',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $movies3D = new Movies3D();
        $movies3D->date         = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $movies3D->title        = $request->title;
        $movies3D->description  = $request->description;
        $movies3D->url          = $request->url;
        $movies3D->slug         = str_slug($request->title, '-');

        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        $image = Image::make($request->file('image'));
        if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
            $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
        }
        $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
        $movies3D->image = $nameImage;

        $movies3D->save();

        $success = "Vídeo adicionado com sucesso.";

        return redirect(route('movies3D'))->with(compact('success'));

    }

    public function getEdit($movies3DId)
    {
        if (! ACL::hasPermission('movies3D', 'edit')) {
            return redirect(route('movies3D'))->withErrors(['Você não tem permissão para editar.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $movies3D = Movies3D::find($movies3DId);
        array_set($movies3D, 'date', Carbon::createFromFormat('Y-m-d', $movies3D->date)->format('d/m/Y'));

        return view('admin.movies3D.edit')->with(compact('imageDetails', 'movies3D'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('movies3D', 'edit')) {
            return redirect(route('movies3D'))->withErrors(['Você não tem permissão para editar.']);
        }

        $this->validate($request, [
            'date'          => 'required|max:10',
            'title'         => 'required|max:100',
            'url'           => 'required',
            'image'         => 'image|mimes:jpeg,gif,png'
        ],
        [
            'date.required'         => 'Informe a data',
            'date.max'              => 'A data do vídeo não pode passar de :max caracteres',
            'title.required'        => 'Informe o título',
            'title.max'             => 'O título não pode passar de :max caracteres',
            'url.required'          => 'Informe o endereço do vídeo',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $movies3D = Movies3D::find($request->movies3DId);
        $movies3D->date         = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $movies3D->title        = $request->title;
        $movies3D->description  = $request->description;
        $movies3D->url          = $request->url;
        $movies3D->slug         = str_slug($request->title, '-');

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
            $movies3D->image = $nameImage;
        }

        $movies3D->save();

        $success = "Vídeo editado com sucesso";

        return redirect(route('movies3D'))->with(compact('success'));

    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('movies3D', 'delete')) {
            return redirect(route('movies3D'))->withErrors(['Você não tem permissão para deletar.']);
        }

        if ($request->image != "") {
            if (File::exists($this->folder . $request->image)) {
                File::delete($this->folder . $request->image);
            }
        }

        Movies3D::find($request->get('movies3DId'))->delete();

        $success = "Vídeo excluído com sucesso.";

        return redirect(route('movies3D'))->with(compact('success'));
    }
}