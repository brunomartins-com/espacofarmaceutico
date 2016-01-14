<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Advertising;
use App\Calls;
use App\Pages;
use App\Photos;
use App\Videos;

class WinnersController extends Controller
{
    public function index()
    {
        $page = 'vencedores';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        if($websiteSettings['winnersOk'] == 0){
            $message = "A página que você tentou acessar está indisponível no momento ou não existe";
            return redirect('/')->with(compact('message'));
        }

        $pages = Pages::where('slug', '=', $page)->first();
        $advertising = Advertising::orderByRaw("RAND()")->get();
        foreach($advertising as $ad){
            array_add($ad, "image", Advertising::imageVideo($ad->url));
            array_set($ad, "url", Advertising::embedVideo($ad->url, 1));
        }
        $calls = Calls::orderByRaw("RAND()")->limit(2)->get();
        $winnersPhotos = Photos::where('photos.winner', '=', 1)
            ->join('users', 'users.id', '=', 'photos.usersId')
            ->addSelect('users.babyName')
            ->addSelect('users.city')
            ->addSelect('users.state')
            ->addSelect('photos.usersId')
            ->addSelect('photos.photo')
            ->addSelect('photos.quantityVotes')
            ->orderBy('photos.quantityVotes', 'DESC')
            ->get();

        $winnersVideos = Videos::where('videos.winner', '=', 1)
            ->join('users', 'users.id', '=', 'videos.usersId')
            ->addSelect('users.babyName')
            ->addSelect('users.city')
            ->addSelect('users.state')
            ->addSelect('videos.url')
            ->addSelect('videos.quantityVotes')
            ->orderBy('videos.quantityVotes', 'DESC')
            ->get();
        foreach($winnersVideos as $wiVi){
            array_add($wiVi, "image", Advertising::imageVideo($wiVi->url));
        }

        return view('website.winners')->with(compact('page', 'websiteSettings', 'pages', 'advertising', 'calls', 'winnersPhotos', 'winnersVideos'));
    }
}