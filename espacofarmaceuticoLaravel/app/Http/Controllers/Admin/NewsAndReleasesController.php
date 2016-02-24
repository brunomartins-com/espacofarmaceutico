<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\NewsAndReleases;

class NewsAndReleasesController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/newsAndReleases/";
        $this->imageWidth   = 600;
        $this->imageHeight  = 175;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('newsAndReleases')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de Notícias e Releases.']);
        }

        $newsAndReleases = NewsAndReleases::orderBy('date', 'DESC')->get();
        foreach($newsAndReleases as $item){
            array_set($item, 'date', Carbon::createFromFormat('Y-m-d', $item->date)->format('d/m/Y'));
        }

        return view('admin.newsAndReleases.index')->with(compact('newsAndReleases'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('newsAndReleases', 'add')) {
            return redirect(route('newsAndReleases'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $imageDetails = [
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        return view('admin.newsAndReleases.add')->with(compact('imageDetails'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('newsAndReleases', 'add')) {
            return redirect(route('newsAndReleases'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'date'          => 'required|max:10',
            'title'         => 'required|max:100',
            'subtitle'      => 'required|max:240',
            'text'          => 'required',
            'tags'          => 'required',
            'image'         => 'image|mimes:jpeg,gif,png'
        ],
        [
            'date.required'         => 'Informe a data',
            'date.max'              => 'A data não pode passar de :max caracteres',
            'title.required'        => 'Informe o título',
            'title.max'             => 'O título não pode passar de :max caracteres',
            'subtitle.required'     => 'Informe o sub-título',
            'subtitle.max'          => 'O sub-título não pode passar de :max caracteres',
            'text.required'         => 'Informe o texto',
            'tags.required'         => 'Informe as tags',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $newsAndReleases = new NewsAndReleases();
        $newsAndReleases->date         = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $newsAndReleases->title        = $request->title;
        $newsAndReleases->subtitle     = $request->subtitle;
        $newsAndReleases->text         = $request->text;
        $newsAndReleases->tags         = $request->tags;
        $newsAndReleases->slug         = str_slug($request->title, '-');

        if ($request->image) {
            //IMAGE
            $extension = $request->image->getClientOriginalExtension();
            $nameImage = Carbon::now()->format('YmdHis').".".$extension;
            $image = Image::make($request->file('image'));
            if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
                $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
            }
            $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
            $newsAndReleases->image = $nameImage;
        }

        $newsAndReleases->save();

        $success = "Notícia/Release adicionada com sucesso.";

        return redirect(route('newsAndReleases'))->with(compact('success'));

    }

    public function getEdit($newsAndReleasesId)
    {
        if (! ACL::hasPermission('newsAndReleases', 'edit')) {
            return redirect(route('newsAndReleases'))->withErrors(['Você não tem permissão para editar.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $newsAndReleases = NewsAndReleases::find($newsAndReleasesId);
        array_set($newsAndReleases, 'date', Carbon::createFromFormat('Y-m-d', $newsAndReleases->date)->format('d/m/Y'));

        return view('admin.newsAndReleases.edit')->with(compact('imageDetails', 'newsAndReleases'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('newsAndReleases', 'edit')) {
            return redirect(route('newsAndReleases'))->withErrors(['Você não tem permissão para editar.']);
        }

        $this->validate($request, [
            'date'          => 'required|max:10',
            'title'         => 'required|max:100',
            'subtitle'      => 'required|max:240',
            'text'          => 'required',
            'tags'          => 'required',
            'image'         => 'image|mimes:jpeg,gif,png'
        ],
        [
            'date.required'         => 'Informe a data',
            'date.max'              => 'A data não pode passar de :max caracteres',
            'title.required'        => 'Informe o título',
            'title.max'             => 'O título não pode passar de :max caracteres',
            'subtitle.required'     => 'Informe o sub-título',
            'subtitle.max'          => 'O sub-título não pode passar de :max caracteres',
            'text.required'         => 'Informe o texto',
            'tags.required'         => 'Informe as tags',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $newsAndReleases = NewsAndReleases::find($request->newsAndReleasesId);
        $newsAndReleases->date         = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $newsAndReleases->title        = $request->title;
        $newsAndReleases->subtitle     = $request->subtitle;
        $newsAndReleases->text         = $request->text;
        $newsAndReleases->tags         = $request->tags;
        $newsAndReleases->slug         = str_slug($request->title, '-');

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
            $newsAndReleases->image = $nameImage;
        }

        $newsAndReleases->save();

        $success = "Notícia/Release editada com sucesso";

        return redirect(route('newsAndReleases'))->with(compact('success'));

    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('newsAndReleases', 'delete')) {
            return redirect(route('newsAndReleases'))->withErrors(['Você não tem permissão para deletar.']);
        }

        if ($request->image != "") {
            if (File::exists($this->folder . $request->image)) {
                File::delete($this->folder . $request->image);
            }
        }

        NewsAndReleases::find($request->get('newsAndReleasesId'))->delete();

        $success = "Notícia/Release excluída com sucesso.";

        return redirect(route('newsAndReleases'))->with(compact('success'));
    }
}