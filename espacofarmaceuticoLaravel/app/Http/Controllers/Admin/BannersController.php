<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\Banners;

class BannersController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/banners/";
        $this->imageWidth   = 700;
        $this->imageHeight  = 650;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('banners')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de banners.']);
        }

        $imageDetails = ['folder' => $this->folder];

        $banners = Banners::orderBy('bannersId', 'DESC')->get();

        return view('admin.banners.index')->with(compact('banners', 'imageDetails'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('banners', 'add')) {
            return redirect(route('banners'))->withErrors(['Você não pode adicionar banners.']);
        }

        $imageDetails = [
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        return view('admin.banners.add')->with(compact('imageDetails'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('banners', 'add')) {
            return redirect(route('banners'))->withErrors(['Você não pode adicionar banners.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'image'         => 'required|image|mimes:jpeg,gif,png'
        ],
        [
            'title.required'=> 'Informe o título do banner',
            'title.max'     => 'O título do banner não pode passar de :max caracteres',
            'image.required'=> 'Envie uma imagem para o banner',
            'image.image'   => 'Envie um formato de imagem válida',
            'image.mimes'   => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $banner = new Banners();
        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        if(!empty($request->url)){
            $banner->url = $request->url;
            if(!empty($request->target)){
                $banner->target = $request->target;
            }else{
                $banner->target = "_self";
            }
        }else{
            $banner->url = "";
            $banner->target = "";
        }

        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        $image = Image::make($request->file('image'));
        if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
            $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
        }
        $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
        $banner->image = $nameImage;

        $banner->save();

        $success = "Banner adicionado com sucesso.";

        return redirect(route('banners'))->with(compact('success'));
    }

    public function getEdit($bannersId)
    {
        if (! ACL::hasPermission('banners', 'edit')) {
            return redirect(route('banners'))->withErrors(['Você não pode editar banners.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $banner = Banners::where('bannersId', '=', $bannersId)->first();

        return view('admin.banners.edit')->with(compact('banner', 'imageDetails'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('banners', 'edit')) {
            return redirect(route('banners'))->withErrors(['Você não pode editar banners.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'image'         => 'image|mimes:jpeg,gif,png'
        ],
        [
            'title.required'=> 'Informe o título do banner',
            'title.max'     => 'O título do banner não pode passar de :max caracteres',
            'image.image'   => 'Envie um formato de imagem válida',
            'image.mimes'   => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $banner = Banners::find($request->bannersId);
        $banner->title      = $request->title;
        $banner->subtitle   = $request->subtitle;
        if(!empty($request->url)){
            $banner->url = $request->url;
            if(!empty($request->target)){
                $banner->target = $request->target;
            }else{
                $banner->target = "_self";
            }
        }else{
            $banner->url = "";
            $banner->target = "";
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
            $banner->image = $nameImage;
        }

        $banner->save();

        $success = "Banner editado com sucesso";

        return redirect(route('banners'))->with(compact('success'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('banners', 'delete')) {
            return redirect(route('banners'))->withErrors(['Você não pode deletar banners.']);
        }

        if ($request->image != "") {
            if (File::exists($this->folder . $request->image)) {
                File::delete($this->folder . $request->image);
            }
        }
        Banners::find($request->get('bannersId'))->delete();

        $success = "Banner excluído com sucesso.";

        return redirect(route('banners'))->with(compact('success'));
    }
}