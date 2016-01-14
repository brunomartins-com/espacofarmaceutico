<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\CommonQuestions;
use App\Concept;
use App\ReducingSpendingOnHealth;
use App\Legislation;
use App\LegislationCategories;
use App\ReliabilityAndQuality;
use App\Pages;

class GenericsMedicationsController extends Controller
{
    public function commonQuestions()
    {
        $page = 'medicamentos-genericos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $texts = CommonQuestions::orderBy('sortorder', 'ASC')->get();

        return view('website.genericsMedications.commonQuestions')->with(compact('page', 'pages', 'websiteSettings', 'texts'));
    }

    public function concept()
    {
        $page = 'medicamentos-genericos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $texts = Concept::orderBy('sortorder', 'ASC')->get();

        return view('website.genericsMedications.concept')->with(compact('page', 'pages', 'websiteSettings', 'texts'));
    }

    public function reducingSpendingOnHealth()
    {
        $page = 'medicamentos-genericos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $texts = ReducingSpendingOnHealth::orderBy('sortorder', 'ASC')->get();

        return view('website.genericsMedications.reducingSpendingOnHealth')->with(compact('page', 'pages', 'websiteSettings', 'texts'));
    }

    public function legislation()
    {
        $page = 'medicamentos-genericos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $categories = LegislationCategories::orderBy('legislationCategoriesId', 'ASC')->get();

        return view('website.genericsMedications.legislation')->with(compact('page', 'pages', 'websiteSettings', 'categories'));
    }

    public function reliabilityAndQuality()
    {
        $page = 'medicamentos-genericos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $texts = ReliabilityAndQuality::orderBy('sortorder', 'ASC')->get();

        return view('website.genericsMedications.reliabilityAndQuality')->with(compact('page', 'pages', 'websiteSettings', 'texts'));
    }
}