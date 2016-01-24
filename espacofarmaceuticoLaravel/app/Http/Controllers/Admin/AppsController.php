<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\Apps;

class AppsController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/supportMaterial/";
        $this->imageWidth   = 77;
        $this->imageHeight  = 77;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('apps')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de Aplicativos.']);
        }

        $apps = Apps::orderBy('sortorder', 'ASC')->get();

        return view('admin.apps.index')->with(compact('apps'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('apps', 'add')) {
            return redirect(route('apps'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $imageDetails = [
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        return view('admin.apps.add')->with(compact('imageDetails'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('apps', 'add')) {
            return redirect(route('apps'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:100',
            'description'   => 'required',
            'image'         => 'required|image|mimes:jpeg,gif,png'
        ],
        [
            'title.required'        => 'Informe o nome do aplicativo',
            'title.max'             => 'O nome do applicativo não pode passar de :max caracteres',
            'description.required'  => 'Informe a descrição do aplicativo',
            'image.required'        => 'Envie a imagem do aplicativo',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $lastSortorder = 0;
        $last = Apps::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $app = new Apps();
        $app->title         = $request->title;
        $app->description   = $request->description;
        $app->iphoneUrl     = $request->iphoneUrl;
        $app->ipadUrl       = $request->ipadUrl;
        $app->androidUrl    = $request->androidUrl;
        $app->sortorder     = $lastSortorder+1;

        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        $image = Image::make($request->file('image'));
        if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
            $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
        }
        $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
        $app->image = $nameImage;

        $app->save();

        $success = "Aplicativo adicionado com sucesso.";

        return redirect(route('apps'))->with(compact('success'));

    }

    public function getEdit($appsId)
    {
        if (! ACL::hasPermission('apps', 'edit')) {
            return redirect(route('apps'))->withErrors(['Você não tem permissão para editar.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $app = Apps::find($appsId);

        return view('admin.apps.edit')->with(compact('imageDetails', 'app'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('apps', 'edit')) {
            return redirect(route('apps'))->withErrors(['Você não tem permissão para editar.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:100',
            'description'   => 'required',
            'image'         => 'image|mimes:jpeg,gif,png'
        ],
        [
            'title.required'        => 'Informe o nome do aplicativo',
            'title.max'             => 'O nome do applicativo não pode passar de :max caracteres',
            'description.required'  => 'Informe a descrição do aplicativo',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $app = Apps::find($request->appsId);
        $app->title         = $request->title;
        $app->description   = $request->description;
        $app->iphoneUrl     = $request->iphoneUrl;
        $app->ipadUrl       = $request->ipadUrl;
        $app->androidUrl    = $request->androidUrl;

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
            $app->image = $nameImage;
        }

        $app->save();

        $success = "Aplicativo editado com sucesso";

        return redirect(route('apps'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('apps', 'edit')) {
            return redirect(route('apps'))->withErrors(['Você não tem permissão para editar a ordem dos aplicativos.']);
        }

        $items = Apps::orderBy('sortorder', 'ASC')
            ->addSelect('appsId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.apps.order')->with(compact('items'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('apps', 'delete')) {
            return redirect(route('apps'))->withErrors(['Você não tem permissão para deletar.']);
        }

        if ($request->image != "") {
            if (File::exists($this->folder . $request->image)) {
                File::delete($this->folder . $request->image);
            }
        }

        Apps::find($request->get('appsId'))->delete();

        $success = "Aplicativo excluído com sucesso.";

        return redirect(route('apps'))->with(compact('success'));
    }
}