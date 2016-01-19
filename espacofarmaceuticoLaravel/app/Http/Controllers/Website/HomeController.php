<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Pages;

class HomeController extends Controller
{
    public function index()
    {
        $page = 'home';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        /*$advertising = Advertising::orderByRaw("RAND()")->get();
        foreach ($advertising as $ad) {
            array_add($ad, "image", Advertising::imageVideo($ad->url));
            array_set($ad, "url", Advertising::embedVideo($ad->url, 1));
        }
        $banners = Banners::orderByRaw("RAND()")->get();
        $calls = Calls::orderByRaw("RAND()")->limit(2)->get();

        return view('website.home')->with(compact('page', 'websiteSettings', 'pages', 'advertising', 'banners', 'calls'));
        */
        return view('website.home')->with(compact('page', 'pages', 'websiteSettings'));
    }
}