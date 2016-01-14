<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use League\Flysystem\Filesystem;

use App\Awards;

class AwardsController extends Controller
{
    public $imageWidth;
    public $imageHeight;
    public $folder;

    public function __construct(){
        $this->imageWidth   = 226;
        $this->imageHeight  = 171;
        $this->folder       = "assets/images/_upload/premios/";
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('awards')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de prêmios.']);
        }

        $awards = Awards::orderBy('awardsId', 'ASC')->get();

        return view('admin.awards.index')->with(compact('awards'));
    }

    public function getEdit($awardsId)
    {
        if (! ACL::hasPermission('awards', 'edit')) {
            return redirect(route('awards'))->withErrors(['Você não pode editar os prêmios.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $awards = Awards::where('awardsId', '=', $awardsId)->first();

        return view('admin.awards.edit')->with(compact('awards', 'imageDetails'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('awards', 'edit')) {
            return redirect(route('awards'))->withErrors(['Você não pode editar os prêmios.']);
        }

        $this->validate($request, [
            'title'     => 'required|max:45',
            'warning'   => 'required',
            'image'     => 'image|mimes:jpeg,bmp,gif,png'
        ],
        [
            'title.required'    => 'Informe o título do prêmio',
            'title.max'         => 'O título do prêmio não pode passar de :max caracteres',
            'warning.required'  => 'Informe o aviso sobre o prêmio',
            'image.image'       => 'Envie um formato de imagem válida',
            'image.mimes'       => 'Formato suportado: .png com fundo transparente'
        ]);

        $award = Awards::find($request->awardsId);
        $award->title   = $request->title;
        $award->warning = $request->warning;

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

            $award->image = $nameImage;
        }

        $award->save();

        $success = "Prêmio editado com sucesso";

        return redirect(route('awards'))->with(compact('success'));

    }
}