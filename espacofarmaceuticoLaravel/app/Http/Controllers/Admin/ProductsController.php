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

use App\Products;

class ProductsController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/produtos/";
        $this->imageWidth   = 284;
        $this->imageHeight  = 290;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('products')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de produtos.']);
        }

        $products = Products::orderBy('sortorder', 'ASC')
            ->addSelect('productsId')
            ->addSelect('title')
            ->addSelect('image')
            ->addSelect('sortorder')
            ->get();

        return view('admin.products.index')->with(compact('products'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('products', 'add')) {
            return redirect(route('products'))->withErrors(['Você não pode adicionar produtos.']);
        }

        $imageDetails = [
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        return view('admin.products.add')->with(compact('imageDetails'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('products', 'add')) {
            return redirect(route('products'))->withErrors(['Você não pode adicionar produtos.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'description'   => 'required',
            'urlBuy'        => 'required',
            'image'         => 'required|image|mimes:png'
        ],
        [
            'title.required'        => 'Informe o título do produto',
            'title.max'             => 'O título do produto não pode passar de :max caracteres',
            'description.required'  => 'Informe a descrição do produto',
            'urlBuy.required'       => 'Informe o link para compra do produto',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formato suportado: .png com fundo transparente'
        ]);

        $lastProduct = Products::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();

        $product = new Products();
        $product->title         = $request->title;
        $product->description   = $request->description;
        $product->urlBuy        = $request->urlBuy;
        $product->sortorder     = $lastProduct->sortorder+1;
        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        Image::make($request->file('image'))->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
        $product->image = $nameImage;

        $product->save();

        $success = "Produto adicionado com sucesso.";

        return redirect(route('products'))->with(compact('success'));

    }

    public function getEdit($productsId)
    {
        if (! ACL::hasPermission('products', 'edit')) {
            return redirect(route('products'))->withErrors(['Você não pode editar produtos.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $product = Products::where('productsId', '=', $productsId)->first();

        return view('admin.products.edit')->with(compact('product', 'imageDetails'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('products', 'edit')) {
            return redirect(route('products'))->withErrors(['Você não pode editar produtos.']);
        }

        $this->validate($request, [
            'title'         => 'required|max:45',
            'description'   => 'required',
            'urlBuy'        => 'required',
            'image'         => 'image|mimes:png'
        ],
        [
            'title.required'        => 'Informe o título do produto',
            'title.max'             => 'O título do produto não pode passar de :max caracteres',
            'description.required'  => 'Informe a descrição do produto',
            'urlBuy.required'       => 'Informe o link para compra do produto',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formato suportado: .png com fundo transparente'
        ]);

        $product = Products::find($request->productsId);
        $product->title         = $request->title;
        $product->description   = $request->description;
        $product->urlBuy        = $request->urlBuy;

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

            $product->image = $nameImage;
        }

        $product->save();

        $success = "Produto editado com sucesso";

        return redirect(route('products'))->with(compact('success'));

    }

    public function getOrder()
    {
        if (! ACL::hasPermission('products', 'edit')) {
            return redirect(route('products'))->withErrors(['Você não pode editar a ordem dos produtos.']);
        }

        $products = Products::orderBy('sortorder', 'ASC')
            ->addSelect('productsId')
            ->addSelect('title')
            ->addSelect('sortorder')
            ->get();

        return view('admin.products.order')->with(compact('products'));
    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('products', 'delete')) {
            return redirect(route('products'))->withErrors(['Você não pode deletar produtos.']);
        }

        if ($request->image != "") {
            if (File::exists($this->folder . $request->image)) {
                File::delete($this->folder . $request->image);
            }
        }
        Products::find($request->get('productsId'))->delete();

        $success = "Produto excluído com sucesso.";

        return redirect(route('products'))->with(compact('success'));
    }
}