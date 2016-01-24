<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\Products;
use App\ProductsCategories;

class ProductsController extends Controller
{
    public $folder;
    public $folderBull;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/products/";
        $this->folderBull   = "assets/bulas/";
        $this->imageWidth   = 300;
        $this->imageHeight  = 290;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('products')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de produtos.']);
        }

        $products = Products::orderBy('name', 'ASC')->get();

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

        $categories = ['' => 'Escolher...'];
        $productsCategories = ProductsCategories::where('type', '=', 0)->orderBy('sortorder', 'ASC')->get();
        foreach ($productsCategories as $productsCategory) {
            $categories[$productsCategory['productsCategoriesId']] = $productsCategory['productsCategoriesName'];
        }

        return view('admin.products.add')->with(compact('imageDetails', 'categories'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('products', 'add')) {
            return redirect(route('products'))->withErrors(['Você não pode adicionar produtos.']);
        }

        $this->validate($request, [
            'name'                  => 'required|max:100',
            'productsCategoriesId'  => 'required',
            'presentation'          => 'required',
            'bull'                  => 'required|mimes:pdf,doc,docx,zip,rar',
            'image'                 => 'required|image|mimes:jpeg,gif,png'
        ],
        [
            'name.required'                 => 'Informe o nome do produto',
            'name.max'                      => 'O nome do produto não pode passar de :max caracteres',
            'productsCategoriesId.required' => 'Escolha a categoria do produto',
            'presentation.required'         => 'Informe a apresentação do produto',
            'bull.required'                 => 'Envie a bula do produto',
            'bull.mimes'                    => 'O formato da bula que você enviou não é válido',
            'image.required'                => 'Envie a imagem do produto',
            'image.image'                   => 'Envie um formato de imagem válida',
            'image.mimes'                   => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $product = new Products();
        $product->name                  = $request->name;
        $product->productsCategoriesId  = $request->productsCategoriesId;
        $product->activePrinciple       = $request->activePrinciple;
        $product->presentation          = $request->presentation;
        $product->slug                  = str_slug($request->name, '-');

        //BULL
        $extension = $request->bull->getClientOriginalExtension();
        $nameBull = Carbon::now()->format('YmdHis').".".$extension;
        $request->file('bull')->move($this->folderBull, $nameBull);
        $product->bull = $nameBull;

        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        $image = Image::make($request->file('image'));
        if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
            $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
        }
        $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
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
            'folderBull'    => $this->folderBull,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $categories = ['' => 'Escolher...'];
        $productsCategories = ProductsCategories::where('type', '=', 0)->orderBy('sortorder', 'ASC')->get();
        foreach ($productsCategories as $productsCategory) {
            $categories[$productsCategory['productsCategoriesId']] = $productsCategory['productsCategoriesName'];
        }

        $product = Products::where('productsId', '=', $productsId)->first();

        return view('admin.products.edit')->with(compact('product', 'imageDetails', 'categories'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('products', 'edit')) {
            return redirect(route('products'))->withErrors(['Você não pode editar produtos.']);
        }

        $this->validate($request, [
            'name'                  => 'required|max:100',
            'productsCategoriesId'  => 'required',
            'presentation'          => 'required',
            'bull'                  => 'mimes:pdf,doc,docx,zip,rar',
            'image'                 => 'image|mimes:jpeg,gif,png'
        ],
        [
            'name.required'                 => 'Informe o nome do produto',
            'name.max'                      => 'O nome do produto não pode passar de :max caracteres',
            'productsCategoriesId.required' => 'Escolha a categoria do produto',
            'presentation.required'         => 'Informe a apresentação do produto',
            'bull.mimes'                    => 'O formato da bula que você enviou não é válido',
            'image.image'                   => 'Envie um formato de imagem válida',
            'image.mimes'                   => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $product = Products::find($request->productsId);
        $product->name                  = $request->name;
        $product->productsCategoriesId  = $request->productsCategoriesId;
        $product->activePrinciple       = $request->activePrinciple;
        $product->presentation          = $request->presentation;
        $product->slug                  = str_slug($request->name, '-');

        if ($request->bull) {
            //DELETE OLD BULL
            if($request->currentBull != ""){
                if(File::exists($this->folderBull.$request->currentBull)){
                    File::delete($this->folderBull.$request->currentBull);
                }
            }
            //BULL
            $extension = $request->bull->getClientOriginalExtension();
            $nameBull = Carbon::now()->format('YmdHis').".".$extension;
            $request->file('bull')->move($this->folderBull, $nameBull);
            $product->bull = $nameBull;
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
            $product->image = $nameImage;
        }

        $product->save();

        $success = "Produto editado com sucesso";

        return redirect(route('products'))->with(compact('success'));

    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('products', 'delete')) {
            return redirect(route('products'))->withErrors(['Você não pode deletar produtos.']);
        }

        if ($request->bull != "") {
            if (File::exists($this->folderBull . $request->bull)) {
                File::delete($this->folderBull . $request->bull);
            }
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

    public function getCategories()
    {
        if (! ACL::hasPermission('products')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de categorias dos produtos.']);
        }

        $categories = ProductsCategories::orderBy('sortorder', 'ASC')->get();

        return view('admin.products.categories')->with(compact('categories'));
    }

    public function getCategoriesAdd()
    {
        if (! ACL::hasPermission('products', 'add')) {
            return redirect(route('productsCategories'))->withErrors(['Você não pode adicionar categorias dos produtos.']);
        }

        return view('admin.products.categoriesAdd');
    }

    public function postCategoriesAdd(Request $request)
    {
        if (! ACL::hasPermission('products', 'add')) {
            return redirect(route('productsCategories'))->withErrors(['Você não pode adicionar categorias dos produtos.']);
        }

        $this->validate($request, [
            'productsCategoriesName'=> 'required|max:100',
            'type'                  => 'required'
        ],
        [
            'productsCategoriesName.required'   => 'Informe o nome da categoria',
            'productsCategoriesName.max'        => 'O nome da categoria não pode passar de :max caracteres',
            'type.required'                     => 'Escolha o tipo da categoria'
        ]);

        $lastSortorder = 0;
        $last = ProductsCategories::orderBy('sortorder', 'DESC')->addSelect('sortorder')->first();
        if(count($last) > 0){
            $lastSortorder = $last->sortorder;
        }

        $category = new ProductsCategories();
        $category->productsCategoriesName   = $request->productsCategoriesName;
        $category->type                     = $request->type;
        $category->productsCategoriesSlug   = str_slug($request->productsCategoriesName, '-');
        $category->sortorder                = $lastSortorder+1;

        $category->save();

        $success = "Categoria adicionada com sucesso.";

        return redirect(route('productsCategories'))->with(compact('success'));

    }

    public function getCategoriesEdit($productsCategoriesId)
    {
        if (! ACL::hasPermission('products', 'edit')) {
            return redirect(route('productsCategories'))->withErrors(['Você não pode editar categoria dos produtos.']);
        }

        $category = ProductsCategories::find($productsCategoriesId);

        return view('admin.products.categoriesEdit')->with(compact('category'));
    }

    public function putCategoriesEdit(Request $request)
    {
        if (! ACL::hasPermission('products', 'edit')) {
            return redirect(route('productsCategories'))->withErrors(['Você não pode editar categoria dos produtos.']);
        }

        $this->validate($request, [
            'productsCategoriesName'=> 'required|max:100',
            'type'                  => 'required'
        ],
        [
            'productsCategoriesName.required'   => 'Informe o nome da categoria',
            'productsCategoriesName.max'        => 'O nome da categoria não pode passar de :max caracteres',
            'type.required'                     => 'Escolha o tipo da categoria'
        ]);

        $category = ProductsCategories::find($request->productsCategoriesId);
        $category->productsCategoriesName   = $request->productsCategoriesName;
        $category->type                     = $request->type;
        $category->productsCategoriesSlug   = str_slug($request->productsCategoriesName, '-');

        $category->save();

        $success = "Categoria editada com sucesso";

        return redirect(route('productsCategories'))->with(compact('success'));

    }

    public function getCategoriesOrder()
    {
        if (! ACL::hasPermission('products', 'edit')) {
            return redirect(route('productsCategories'))->withErrors(['Você não pode editar a ordem das categorias dos produtos.']);
        }

        $categories = ProductsCategories::orderBy('sortorder', 'ASC')->get();

        return view('admin.products.categoriesOrder')->with(compact('categories'));
    }

    public function deleteCategories(Request $request)
    {
        if (! ACL::hasPermission('products', 'delete')) {
            return redirect(route('productsCategories'))->withErrors(['Você não pode deletar categorias dos produtos.']);
        }

        ProductsCategories::deleteProductsByCategory($request->get('productsCategoriesId'));
        ProductsCategories::find($request->get('productsCategoriesId'))->delete();

        $success = "Categoria excluída com sucesso.";

        return redirect(route('productsCategories'))->with(compact('success'));
    }
}