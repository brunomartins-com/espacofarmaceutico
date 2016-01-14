<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Photos;

class PhotosController extends Controller
{
    public $folder;

    public function __construct(){
        $this->folder = "assets/images/_upload/fotos/";
    }

    public function getIndex()
    {
        if (! ACL::hasPermission('photos')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para acessar a página de fotos.']);
        }

        $folder = $this->folder;

        $photos = Photos::join('users', 'users.id', '=', 'photos.usersId')
            ->orderBy('photos.created_at', 'DESC')
            ->addSelect('photos.photosId')
            ->addSelect('photos.created_at')
            ->addSelect('photos.photo')
            ->addSelect('photos.urlInstagram')
            ->addSelect('photos.status')
            ->addSelect('users.id AS userId')
            ->addSelect('users.babyName')
            ->addSelect('users.name')
            ->get();

        return view('admin.photos.index')->with(compact('photos', 'folder'));
    }

    public function putStatus(Request $request)
    {
        if (! ACL::hasPermission('photos', 'edit')) {
            return redirect(route('photos'))->withErrors(['Você não tem permissão para editar o status das fotos.']);
        }
        $photo = Photos::find($request->photosId);
        $photo->status = $request->status;
        $photo->urlInstagram = "";
        $photo->save();

        $success = "Status da foto editada com sucesso!";

        return redirect(route('photos'))->with(compact('success'));
    }

    public function getFinalist($photosId)
    {
        if (! ACL::hasPermission('photos', 'edit')) {
            return redirect(route('photos'))->withErrors(['Você não tem permissão para editar o status das fotos.']);
        }

        $folder = $this->folder;

        $photo = Photos::where('photos.photosId', '=', $photosId)
            ->join('users', 'users.id', '=', 'photos.usersId')
            ->orderBy('photos.created_at', 'DESC')
            ->addSelect('photos.photosId')
            ->addSelect('photos.created_at')
            ->addSelect('photos.photo')
            ->addSelect('photos.urlInstagram')
            ->addSelect('photos.status')
            ->addSelect('users.id AS userId')
            ->addSelect('users.babyName')
            ->addSelect('users.name')
            ->first();

        return view('admin.photos.finalist')->with(compact('photo', 'folder'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('photos', 'edit')) {
            return redirect(route('photos'))->withErrors(['Você não tem permissão para editar o status das fotos.']);
        }

        $photo = Photos::find($request->photosId);
        $photo->urlInstagram = $request->urlInstagram;
        $photo->status = 2;
        $photo->save();

        $success = "Você inseriu a URL do Instagram para a finalista com sucesso";

        return redirect(route('photos'))->with(compact('success'));

    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('photos', 'delete')) {
            return redirect(route('photos'))->withErrors(['Você não tem permissão para deletar fotos.']);
        }

        if ($request->photo != "") {
            if (File::exists($this->folder.$request->usersId.'/thumb_'.$request->photo)) {
                File::delete($this->folder.$request->usersId.'/thumb_'.$request->photo);
            }
            if (File::exists($this->folder.$request->usersId.'/'.$request->photo)) {
                File::delete($this->folder.$request->usersId.'/'.$request->photo);
            }
        }
        Photos::find($request->get('photosId'))->delete();

        $success = "Foto excluída com sucesso.";

        return redirect(route('photos'))->with(compact('success'));
    }
}