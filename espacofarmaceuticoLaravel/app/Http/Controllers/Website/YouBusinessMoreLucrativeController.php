<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Texts;
use App\Pages;

class YouBusinessMoreLucrativeController extends Controller
{
    public function index()
    {
        $page = 'seu-negocio-mais-lucrativo';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(18);

        return view('website.youBusinessMoreLucrative.index')->with(compact('page', 'pages', 'websiteSettings', 'text'));
    }
}