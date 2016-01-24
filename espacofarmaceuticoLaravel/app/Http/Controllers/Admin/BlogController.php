<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\Blog;

class BlogController extends Controller
{
    public $folder;
    public $imageWidth;
    public $imageHeight;

    public function __construct(){
        $this->folder       = "assets/images/_upload/blog/";
        $this->imageWidth   = 600;
        $this->imageHeight  = 175;
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('blog')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página de Blog.']);
        }

        $blog = Blog::orderBy('date', 'DESC')->get();
        foreach($blog as $item){
            array_set($item, 'date', Carbon::createFromFormat('Y-m-d', $item->date)->format('d/m/Y'));
        }

        return view('admin.blog.index')->with(compact('blog'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('blog', 'add')) {
            return redirect(route('blog'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $imageDetails = [
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        return view('admin.blog.add')->with(compact('imageDetails'));
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('blog', 'add')) {
            return redirect(route('blog'))->withErrors(['Você não tem permissão para adicionar.']);
        }

        $this->validate($request, [
            'date'          => 'required|max:10',
            'title'         => 'required|max:100',
            'subtitle'      => 'required|max:240',
            'text'          => 'required',
            'tags'          => 'required',
            'image'         => 'required|image|mimes:jpeg,gif,png'
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
            'image.required'        => 'Envie a imagem',
            'image.image'           => 'Envie um formato de imagem válida',
            'image.mimes'           => 'Formatos suportados: .jpg, .gif e .png'
        ]);

        $blog = new Blog();
        $blog->date         = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $blog->title        = $request->title;
        $blog->subtitle     = $request->subtitle;
        $blog->text         = $request->text;
        $blog->autorSource  = $request->autorSource;
        $blog->tags         = $request->tags;
        $blog->slug         = str_slug($request->title, '-');

        //IMAGE
        $extension = $request->image->getClientOriginalExtension();
        $nameImage = Carbon::now()->format('YmdHis').".".$extension;
        $image = Image::make($request->file('image'));
        if($request->imageCropAreaW > 0 or $request->imageCropAreaH > 0 or $request->imagePositionX or $request->imagePositionY){
            $image->crop($request->imageCropAreaW, $request->imageCropAreaH, $request->imagePositionX, $request->imagePositionY);
        }
        $image->resize($this->imageWidth, $this->imageHeight)->save($this->folder.$nameImage);
        $blog->image = $nameImage;

        $blog->save();

        $success = "Matéria adicionada com sucesso.";

        return redirect(route('blog'))->with(compact('success'));

    }

    public function getEdit($blogId)
    {
        if (! ACL::hasPermission('blog', 'edit')) {
            return redirect(route('blog'))->withErrors(['Você não tem permissão para editar.']);
        }

        $imageDetails = [
            'folder'        => $this->folder,
            'imageWidth'    => $this->imageWidth,
            'imageHeight'   => $this->imageHeight
        ];

        $blog = Blog::find($blogId);
        array_set($blog, 'date', Carbon::createFromFormat('Y-m-d', $blog->date)->format('d/m/Y'));

        return view('admin.blog.edit')->with(compact('imageDetails', 'blog'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('blog', 'edit')) {
            return redirect(route('blog'))->withErrors(['Você não tem permissão para editar.']);
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

        $blog = Blog::find($request->blogId);
        $blog->date         = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $blog->title        = $request->title;
        $blog->subtitle     = $request->subtitle;
        $blog->text         = $request->text;
        $blog->autorSource  = $request->autorSource;
        $blog->tags         = $request->tags;
        $blog->slug         = str_slug($request->title, '-');

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
            $blog->image = $nameImage;
        }

        $blog->save();

        $success = "Matéria editada com sucesso";

        return redirect(route('blog'))->with(compact('success'));

    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('blog', 'delete')) {
            return redirect(route('blog'))->withErrors(['Você não tem permissão para deletar.']);
        }

        if ($request->image != "") {
            if (File::exists($this->folder . $request->image)) {
                File::delete($this->folder . $request->image);
            }
        }

        Blog::find($request->get('blogId'))->delete();

        $success = "Matéria excluída com sucesso.";

        return redirect(route('blog'))->with(compact('success'));
    }
}