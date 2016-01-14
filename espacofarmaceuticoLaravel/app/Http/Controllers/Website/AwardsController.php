<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Advertising;
use App\Calls;
use App\Awards;
use App\Pages;

class AwardsController extends Controller
{
    public function index()
    {
        $page = 'premios';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();
        $advertising = Advertising::orderByRaw("RAND()")->get();
        foreach($advertising as $ad){
            array_add($ad, "image", Advertising::imageVideo($ad->url));
            array_set($ad, "url", Advertising::embedVideo($ad->url, 1));
        }
        $calls = Calls::orderByRaw("RAND()")->limit(2)->get();
        $awards = Awards::orderBy('awardsId', 'ASC')->get();

        return view('website.awards')->with(compact('page', 'websiteSettings', 'pages', 'advertising', 'calls', 'awards'));
    }
}