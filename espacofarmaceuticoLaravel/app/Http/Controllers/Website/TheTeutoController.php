<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Texts;
use App\Pages;

class TheTeutoController extends Controller
{
    public function index()
    {
        $page = 'o-teuto';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(1);
        $movie = Texts::find(17);

        return view('website.theTeuto.index')->with(compact('page', 'pages', 'websiteSettings', 'text', 'movie'));
    }
}