<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Photos;
use App\Videos;
use App\Advertising;

class WinnersController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('winners')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para acessar a página de vencedores.']);
        }

        $photoWinners = Photos::where('winner', '=', 1)
            ->join('users', 'usersId', '=', 'id')
            ->orderBy('quantityVotes', 'DESC')
            ->addSelect('photosId')
            ->addSelect('usersId')
            ->addSelect('babyName')
            ->addSelect('name')
            ->addSelect('city')
            ->addSelect('state')
            ->addSelect('photo')
            ->addSelect('quantityVotes')
            ->get();

        $videoWinners = Videos::where('winner', '=', 1)
            ->join('users', 'usersId', '=', 'id')
            ->orderBy('quantityVotes', 'DESC')
            ->addSelect('videosId')
            ->addSelect('babyName')
            ->addSelect('name')
            ->addSelect('city')
            ->addSelect('state')
            ->addSelect('url')
            ->addSelect('quantityVotes')
            ->get();
        foreach($videoWinners as $videoWinner){
            array_add($videoWinner, "image", Advertising::imageVideo($videoWinner->url));
        }

        $qtdWinners = $photoWinners->count()+$videoWinners->count();

        return view('admin.winners.index')->with(compact('photoWinners', 'videoWinners', 'qtdWinners'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('winners', 'add')) {
            return redirect(route('winners'))->withErrors(['Você não tem permissão para adicionar vencedores.']);
        }

        $countPhotoWinners = Photos::where('winner', '=', 1)->count();
        $countVideoWinners = Videos::where('winner', '=', 1)->count();

        return view('admin.winners.add')->with(compact('countPhotoWinners', 'countVideoWinners'));
    }

    public function postCategory(Request $request)
    {
        if($request->table == 'videos'){
            $consult = Videos::where('status', '=', 1)
                ->where('winner', '=', 0)
                ->join('users', 'videos.usersId', '=', 'id')
                ->get();
        }else if($request->table == 'photos'){
            $consult = Photos::where('status', '=', 1)
                ->where('winner', '=', 0)
                ->join('users', 'photos.usersId', '=', 'id')
                ->get();
        }

        return json_encode($consult);
    }

    public function putAdd(Request $request)
    {
        if (!ACL::hasPermission('winners', 'add')) {
            return redirect(route('winners'))->withErrors(['Você não tem permissão para adicionar vencedores.']);
        }

        $this->validate($request, [
            'quantityVotes' => 'required|numeric',
        ],[
            'quantityVotes.required'=> 'Informe a posição',
            'quantityVotes.numeric'  => 'Somente números são aceitos',
        ]);

        if($request->category == 'videos'){
            $videos = Videos::find($request->videosId);
            $videos->winner = 1;
            $videos->quantityVotes = $request->quantityVotes;
            $videos->save();
        }else if($request->category == 'photos'){
            $photos = Photos::find($request->photosId);
            $photos->winner = 1;
            $photos->quantityVotes = $request->quantityVotes;
            $photos->save();
        }

        $success = "Vencedor cadastrado com sucesso.";

        return redirect(url('/admin/vencedores#'.$request->category))->with(compact('success'));
    }

    public function putDelete(Request $request)
    {
        if (! ACL::hasPermission('winners', 'delete')) {
            return redirect(url('/admin/vencedores#'.$request->table))->withErrors(['Você não tem permissão para excluir '.$request->table.'.']);
        }

        if(isset($request->videosId)){
            $videos = Videos::find($request->videosId);
            $videos->winner = 0;
            $videos->quantityVotes = "";
            $videos->save();
        }elseif(isset($request->photosId)){
            $photos = Photos::find($request->photosId);
            $photos->winner = 0;
            $photos->quantityVotes = "";
            $photos->save();
        }

        $success = "Vencedor excluído com sucesso!";

        return redirect(url('/admin/vencedores#'.$request->table))->with(compact('success'));
    }
}