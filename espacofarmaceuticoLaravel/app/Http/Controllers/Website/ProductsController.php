<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Advertising;
use App\Calls;
use App\Products;
use App\Pages;

class ProductsController extends Controller
{
    public function index()
    {
        $page = 'produtos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();
        $advertising = Advertising::orderByRaw("RAND()")->get();
        foreach($advertising as $ad){
            array_add($ad, "image", Advertising::imageVideo($ad->url));
            array_set($ad, "url", Advertising::embedVideo($ad->url, 1));
        }
        $calls = Calls::orderByRaw("RAND()")->limit(2)->get();
        $products = Products::orderBy('sortorder', 'ASC')->get();

        return view('website.products')->with(compact('page', 'websiteSettings', 'pages', 'advertising', 'calls', 'products'));
    }
}