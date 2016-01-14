<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Texts;
use App\Pages;

class VisitWellController extends Controller
{
    public function index()
    {
        $page = 'visite-bem';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(20);

        return view('website.visitWell.index')->with(compact('page', 'pages', 'websiteSettings', 'text'));
    }

    public function photos()
    {
        $page = 'visite-bem';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(20);

        return view('website.visitWell.photos')->with(compact('page', 'pages', 'websiteSettings', 'text'));
    }

    public function scheduleYourVisit()
    {
        $page = 'visite-bem';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(20);

        return view('website.visitWell.scheduleYourVisit')->with(compact('page', 'pages', 'websiteSettings', 'text'));
    }
}