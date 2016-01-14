<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Advertising;
use App\Calls;
use App\WinnersLastYear;
use App\Pages;

class Winners2014Controller extends Controller
{
    public function index()
    {
        $page = 'ganhadores-2014';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();
        $advertising = Advertising::orderByRaw("RAND()")->get();
        foreach($advertising as $ad){
            array_add($ad, "image", Advertising::imageVideo($ad->url));
            array_set($ad, "url", Advertising::embedVideo($ad->url, 1));
        }
        $calls = Calls::orderByRaw("RAND()")->limit(2)->get();
        $winners = WinnersLastYear::orderBy('winnersLastYearId', 'ASC')->get();

        return view('website.winners2014')->with(compact('page', 'websiteSettings', 'pages', 'advertising', 'calls', 'winners'));
    }
}