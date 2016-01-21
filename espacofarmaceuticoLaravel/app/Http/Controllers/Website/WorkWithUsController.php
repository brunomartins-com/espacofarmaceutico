<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Texts;
use App\Pages;
use App\WorkWithUsVacancy;

class WorkWithUsController extends Controller
{
    public function index()
    {
        $page = 'trabalhe-conosco';
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $link = Texts::find(5);
        $mainText = Texts::find(16);
        $complementText = Texts::find(12);

        $vacancies = WorkWithUsVacancy::orderBy('sortorder', 'asc')->get();

        return view('website.workWithUs.index')->with(compact('page', 'websiteSettings', 'pages', 'link', 'mainText', 'complementText', 'vacancies'));
    }
}