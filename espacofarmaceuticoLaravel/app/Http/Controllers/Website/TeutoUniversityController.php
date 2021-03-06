<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Texts;
use App\Pages;

class TeutoUniversityController extends Controller
{
    public function index()
    {
        $page = 'universidade-teuto';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(4);

        return view('website.teutoUniversity.index')->with(compact('page', 'pages', 'websiteSettings', 'text'));
    }
}