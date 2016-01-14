<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Texts;
use App\Pages;

class RegionalCouncilsController extends Controller
{
    public function index()
    {
        $page = 'conselhos-regionais';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(10);

        return view('website.regionalCouncils.index')->with(compact('page', 'pages', 'websiteSettings', 'text'));
    }
}