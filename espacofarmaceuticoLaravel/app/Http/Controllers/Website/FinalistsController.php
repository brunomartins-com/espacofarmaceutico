<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Advertising;
use App\Calls;
use App\Pages;
use App\Photos;
use App\Videos;

class FinalistsController extends Controller
{
    public function index()
    {
        $page = 'finalistas';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        if($websiteSettings['votingOk'] == 0){
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
        $finalistsPhotos = Photos::where('photos.status', '=', 2)
            ->where('urlInstagram', '!=', '')
            ->join('users', 'users.id', '=', 'photos.usersId')
            ->addSelect('users.babyName')
            ->addSelect('users.city')
            ->addSelect('users.state')
            ->addSelect('photos.usersId')
            ->addSelect('photos.photo')
            ->addSelect('photos.urlInstagram')
            ->orderByRaw('RAND()')
            ->get();

        $finalistsVideos = Videos::where('videos.status', '=', 2)
            ->where('urlInstagram', '!=', '')
            ->join('users', 'users.id', '=', 'videos.usersId')
            ->addSelect('users.babyName')
            ->addSelect('users.city')
            ->addSelect('users.state')
            ->addSelect('videos.url')
            ->addSelect('videos.urlInstagram')
            ->orderByRaw('RAND()')
            ->get();
        foreach($finalistsVideos as $wiVi){
            array_add($wiVi, "image", Advertising::imageVideo($wiVi->url));
        }

        return view('website.finalists')->with(compact('page', 'websiteSettings', 'pages', 'advertising', 'calls', 'finalistsPhotos', 'finalistsVideos'));
    }
}