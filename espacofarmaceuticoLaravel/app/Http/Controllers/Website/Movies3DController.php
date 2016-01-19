<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Movies3D;
use App\Pages;

class Movies3DController extends Controller
{
    public function index()
    {
        $page = 'videos-3d';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $movies3D = Movies3D::orderBy('date', 'desc')
            ->addSelect('movies3DId')
            ->addSelect('title')
            ->addSelect('date')
            ->addSelect('image')
            ->addSelect('slug')
            ->paginate(4);
        foreach($movies3D as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        $moviesMoreWatched = Movies3D::orderBy('watch', 'desc')
            ->addSelect('movies3DId')
            ->addSelect('title')
            ->addSelect('date')
            ->addSelect('slug')
            ->limit(5)
            ->get();
        foreach($moviesMoreWatched as $itemMoreWatched){
            array_set($itemMoreWatched, "date", Carbon::createFromFormat('Y-m-d', $itemMoreWatched->date));
        }

        return view('website.movies3D.index')->with(compact('page', 'pages', 'websiteSettings', 'movies3D', 'moviesMoreWatched'));
    }

    public function watch(Request $request)
    {
        $page = 'videos-3d';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();
        $date = $request->year.'-'.$request->month.'-'.$request->day;
        $movie3D = Movies3D::where('date', '=', $date)
            ->where('slug', '=', $request->slug)
            ->first();
        array_set($movie3D, "date", Carbon::createFromFormat('Y-m-d', $movie3D->date));
        array_set($movie3D, "url", Movies3D::embedVideo($movie3D->url, true));

        //INCREMENT
        Movies3D::find($movie3D->movies3DId)->increment('watch');

        //MORE BLOG
        $moreMovies3D = Movies3D::orderBy('date', 'desc')
            ->where('movies3DId', '!=', $movie3D->movies3DId)
            ->limit(2)
            ->addSelect('title')
            ->addSelect('date')
            ->addSelect('slug')
            ->get();
        foreach($moreMovies3D as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        return view('website.movies3D.intern')->with(compact('page', 'pages', 'websiteSettings', 'movie3D', 'moreMovies3D'));
    }
}