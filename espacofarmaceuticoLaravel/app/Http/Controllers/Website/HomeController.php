<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Pages;
use App\Banners;
use App\NewsAndReleases;
use App\Blog;
use App\Products;
use App\Texts;
use App\Calls;

class HomeController extends Controller
{
    public $homeTextId;
    public $linkId;
    public $imageId;
    public $videoTheTeuto;

    public function __construct(){
        $this->homeTextId       = 14;
        $this->linkId           = 5;
        $this->imageId          = 15;
        $this->videoTheTeuto    = 17;
    }

    public function index()
    {
        $page = 'home';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $banners = Banners::orderByRaw("RAND()")->get();
        $newsAndReleases = NewsAndReleases::orderBy('date', 'desc')->limit(6)->get();
        foreach($newsAndReleases as $item){
            array_set($item, 'date', Carbon::createFromFormat('Y-m-d', $item->date));
        }
        $blog = Blog::orderBy('date', 'desc')->limit(3)->get();
        foreach($blog as $item){
            array_set($item, 'date', Carbon::createFromFormat('Y-m-d', $item->date));
        }
        $products = Products::getHomeCategories();
        $workWithUsHomeText = Texts::find($this->homeTextId);
        $workWithUsLink = Texts::find($this->linkId);
        $workWithUsImage = Texts::find($this->imageId);
        $calls = Calls::orderByRaw("RAND()")->limit(3)->get();
        $videoTheTeuto = Texts::find($this->videoTheTeuto);

        return view('website.home')->with(compact('page', 'pages', 'websiteSettings', 'banners', 'newsAndReleases', 'blog', 'products', 'workWithUsHomeText', 'workWithUsLink', 'workWithUsImage', 'calls', 'videoTheTeuto'));
    }
}