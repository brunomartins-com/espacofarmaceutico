<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Blog;
use App\NewsAndReleases;
use App\Pages;

class SearchController extends Controller
{
    public function post(Request $request)
    {
        $page = 'busca';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        if(!$request->inputSearch){
            return redirect('/')->with('message', 'Informe algum conteúdo para a busca.');
        }

        $blog = Blog::where('title', 'LIKE', '%'.$request->inputSearch.'%')
            ->orWhere('subtitle', 'LIKE', '%'.$request->inputSearch.'%')
            ->orderBy('date', 'desc')
            ->get();
        foreach($blog as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        $newsAndReleases = NewsAndReleases::where('title', 'LIKE', '%'.$request->inputSearch.'%')
            ->orWhere('subtitle', 'LIKE', '%'.$request->inputSearch.'%')
            ->orderBy('date', 'desc')
            ->get();
        foreach($newsAndReleases as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        return view('website.search.index')->with(compact('page', 'pages', 'websiteSettings', 'request', 'blog', 'newsAndReleases'));
    }
}