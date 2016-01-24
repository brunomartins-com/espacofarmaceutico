<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\Texts;

class WorkWithUsController extends Controller
{
    public $folder;
    public $mainTextId;
    public $complementTextId;
    public $homeTextId;
    public $linkId;
    public $imageId;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder           = "assets/images/_upload/workWithUs/";
        $this->mainTextId       = 16;
        $this->complementTextId = 12;
        $this->homeTextId       = 14;
        $this->linkId           = 5;
        $this->imageId          = 15;
        $this->imageWidth       = 311;
        $this->imageHeight      = 105;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('workWithUs') and ! ACL::hasPermission('workWithUs', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar a página Trabalhe Conosco.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $mainText = Texts::find($this->mainTextId);

        $complementText = Texts::find($this->complementTextId);

        $homeText = Texts::find($this->homeTextId);

        $link = Texts::find($this->linkId);

        $image = Texts::find($this->imageId);

        return view('admin.workWithUs.index')->with(compact('mainText', 'complementText', 'homeText', 'link', 'image', 'imageDetails'));
    }

    public function putUpdate(Request $request)
    {
        if (! ACL::hasPermission('workWithUs', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar a página Trabalhe Conosco.']);
        }

        $this->validate($request, [
            'mainText'          => 'required',
            'complementText'    => 'required',
            'homeText'          => 'required',
            'link'              => 'required',
            'image'             => 'image|mimes:jpeg,gif,png'
        ],
        [
            'mainText.required'          => 'Informe o texto principal',
            'complementText.required'    => 'Informe o texto complementar',
            'homeText.required'          => 'Informe o texto da home',
            'link.required'              => 'Informe o link externo',
            'image.image'                => 'Envie um formato de imagem válida',
            'image.mimes'                => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $mainText = Texts::find($this->mainTextId);
        $mainText->text = $request->mainText;
        $mainText->save();

        $complementText = Texts::find($this->complementTextId);
        $complementText->text = $request->complementText;
        $complementText->save();

        $homeText = Texts::find($this->homeTextId);
        $homeText->text = $request->homeText;
        $homeText->save();

        $link = Texts::find($this->linkId);
        $link->text = $request->link;
        $link->save();

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

            $imageDB = Texts::find($this->imageId);
            $imageDB->text = $nameImage;
            $imageDB->save();
        }

        $success = "Dados editados com sucesso!";

        return redirect(route('workWithUs'))->with(compact('success'));
    }
}