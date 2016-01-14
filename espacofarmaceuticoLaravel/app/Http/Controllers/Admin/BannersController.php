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

use App\Banners;

class BannersController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/banners/";
        $this->imageWidth   = 825;
        $this->imageHeight  = 675;
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
            'image'         => 'required|image|mimes:png'
        ],
        [
            'title.required'=> 'Informe o título do banner',
            'title.max'     => 'O título do banner não pode passar de :max caracteres',
            'image.image'   => 'Envie um formato de imagem válida',
            'image.mimes'   => 'Formato suportado: .png com fundo transparente'
        ]);

        $banner = new Banners();
        $banner->title = $request->title;
        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        Image::make($request->file('image'))->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);

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
            'image'         => 'image|mimes:png'
        ],
        [
            'title.required'=> 'Informe o título do banner',
            'title.max'     => 'O título do banner não pode passar de :max caracteres',
            'image.image'   => 'Envie um formato de imagem válida',
            'image.mimes'   => 'Formato suportado: .png com fundo transparente'
        ]);

        $banner = Banners::find($request->bannersId);
        $banner->title         = $request->title;

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