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

use App\WinnersLastYear;

class Winners2014Controller extends Controller
{
    public $folder;
    public $photoWidth;
    public $photoHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/ganhadores-2014/";
        $this->photoWidth   = 231;
        $this->photoHeight  = 231;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('winners2014')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de ganhadores 2014.']);
        }

        $winners = WinnersLastYear::orderBy('winnersLastYearId', 'ASC')->get();

        return view('admin.winners2014.index')->with(compact('winners'));
    }

    public function getEdit($winnersLastYearId)
    {
        if (! ACL::hasPermission('winners2014', 'edit')) {
            return redirect(route('winners2014'))->withErrors(['Você não pode editar os ganhadores de 2014.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'photoWidth'    => $this->photoWidth,
            'photoHeight'   => $this->photoHeight
        ];

        $winner = WinnersLastYear::where('winnersLastYearId', '=', $winnersLastYearId)->first();

        return view('admin.winners2014.edit')->with(compact('winner', 'imageDetails'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('winners2014', 'edit')) {
            return redirect(route('winners2014'))->withErrors(['Você não pode editar os ganhadores de 2014.']);
        }

        $this->validate($request, [
            'category'      => 'required',
            'position'      => 'required',
            'name'          => 'required|max:50',
            'city'          => 'required|max:45',
            'state'         => 'required|max:2|min:2',
            'quantityVotes' => 'required|numeric',
            'image'         => 'image|mimes:jpeg,bmp,gif,png'
        ],
        [
            'category.required'         => 'Escolha a categoria',
            'position.required'         => 'Escolha a posição',
            'name.required'             => 'Informe o nome do ganhador',
            'name.max'                  => 'O nome do ganhador não pode passar de :max caracteres',
            'city.required'             => 'Informe o nome da cidade do ganhador',
            'city.max'                  => 'O nome da cidade não pode passar de :max caracteres',
            'state.required'            => 'Informe o Estado do ganhador',
            'state.max'                 => 'O UF do Estado deve ter apenas :max caracteres',
            'state.min'                 => 'O UF do Estado deve ter apenas :min caracteres',
            'quantityVotes.required'    => 'Informe a quantidade de votos',
            'quantityVotes.numeric'     => 'Somente números são aceitos',
            'image.image'               => 'Envie um formato de imagem válida',
            'image.mimes'               => 'Formatos suportados: .jpg, .gif, .bmp e .png'
        ]);

        $winner = WinnersLastYear::find($request->winnersLastYearId);
        $winner->category       = $request->category;
        $winner->position       = $request->position;
        $winner->name           = $request->name;
        $winner->city           = $request->city;
        $winner->state          = $request->state;
        $winner->quantityVotes  = $request->quantityVotes;

        if ($request->photo) {
            //DELETE OLD PHOTO
            if($request->currentPhoto != ""){
                if(File::exists($this->folder.$request->currentPhoto)){
                    File::delete($this->folder.$request->currentPhoto);
                }
            }
            $extension = $request->photo->getClientOriginalExtension();
            $namePhoto = Carbon::now()->format('YmdHis').".".$extension;
            $img = Image::make($request->file('photo'));
            if($request->photoCropAreaW > 0 or $request->photoCropAreaH > 0 or $request->photoPositionX or $request->photoPositionY){
                $img->crop($request->photoCropAreaW, $request->photoCropAreaH, $request->photoPositionX, $request->photoPositionY);
            }
            $img->resize($this->photoWidth, $this->photoHeight)->save($this->folder.$namePhoto);

            $winner->photo = $namePhoto;
        }

        $winner->save();

        $success = "Ganhador editado com sucesso";

        return redirect(route('winners2014'))->with(compact('success'));

    }
}