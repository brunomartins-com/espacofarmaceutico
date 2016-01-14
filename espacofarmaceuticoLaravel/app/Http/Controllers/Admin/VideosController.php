<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Videos;
use App\Advertising;

class VideosController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('videos')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para acessar a página de vídeos.']);
        }

        $videos = Videos::join('users', 'users.id', '=', 'videos.usersId')
            ->orderBy('videos.created_at', 'DESC')
            ->addSelect('videos.videosId')
            ->addSelect('videos.created_at')
            ->addSelect('videos.url')
            ->addSelect('videos.urlInstagram')
            ->addSelect('videos.status')
            ->addSelect('users.id AS userId')
            ->addSelect('users.babyName')
            ->addSelect('users.name')
            ->get();
        foreach($videos as $video){
            array_add($video, "image", Advertising::imageVideo($video->url));
        }

        return view('admin.videos.index')->with(compact('videos'));
    }

    public function putStatus(Request $request)
    {
        if (! ACL::hasPermission('videos', 'edit')) {
            return redirect(route('videos'))->withErrors(['Você não tem permissão para editar o status dos vídeos.']);
        }
        $video = Videos::find($request->videosId);
        $video->status = $request->status;
        $video->urlInstagram = "";
        $video->save();

        $success = "Status do vídeo editado com sucesso!";

        return redirect(route('videos'))->with(compact('success'));
    }

    public function getFinalist($videosId)
    {
        if (! ACL::hasPermission('videos', 'edit')) {
            return redirect(route('videos'))->withErrors(['Você não tem permissão para editar o status dos vídeos.']);
        }

        $video = Videos::where('videos.videosId', '=', $videosId)
            ->join('users', 'users.id', '=', 'videos.usersId')
            ->orderBy('videos.created_at', 'DESC')
            ->addSelect('videos.videosId')
            ->addSelect('videos.created_at')
            ->addSelect('videos.url')
            ->addSelect('videos.urlInstagram')
            ->addSelect('videos.status')
            ->addSelect('users.id AS userId')
            ->addSelect('users.babyName')
            ->addSelect('users.name')
            ->first();

            array_add($video, "image", Advertising::imageVideo($video->url));

        return view('admin.videos.finalist')->with(compact('video'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('videos', 'edit')) {
            return redirect(route('videos'))->withErrors(['Você não tem permissão para editar o status dos vídeos.']);
        }

        $video = Videos::find($request->videosId);
        $video->urlInstagram = $request->urlInstagram;
        $video->status = 2;
        $video->save();

        $success = "Você inseriu a URL do Instagram para a finalista com sucesso";

        return redirect(route('videos'))->with(compact('success'));

    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('videos', 'delete')) {
            return redirect(route('videos'))->withErrors(['Você não tem permissão para deletar vídeos.']);
        }

        Videos::find($request->get('videosId'))->delete();

        $success = "Vídeo excluído com sucesso.";

        return redirect(route('videos'))->with(compact('success'));
    }
}