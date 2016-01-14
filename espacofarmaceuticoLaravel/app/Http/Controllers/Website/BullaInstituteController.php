<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Texts;
use App\Pages;

class BullaInstituteController extends Controller
{
    public function index()
    {
        $page = 'instituto-bulla';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(19);

        return view('website.bullaInstitute.index')->with(compact('page', 'pages', 'websiteSettings', 'text'));
    }
}