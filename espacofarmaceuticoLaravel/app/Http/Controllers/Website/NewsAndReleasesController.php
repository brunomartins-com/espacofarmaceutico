<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\NewsAndReleases;
use App\Pages;

class NewsAndReleasesController extends Controller
{
    public function index(Request $request)
    {
        $page = 'noticias-e-releases';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();
        $newsAndReleases = NewsAndReleases::orderBy('date', 'desc');
        if(!empty($request->palavra)){
            $newsAndReleases = $newsAndReleases->where('tags', 'LIKE', '%'.$request->palavra.'%');
        }
        $newsAndReleases = $newsAndReleases->paginate(4);
        if(!empty($request->palavra)) {
            $newsAndReleases->setPath('busca?palavra=' . $request->palavra . '&');
        }
        foreach($newsAndReleases as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        $tagsMoreSearchedConsult = NewsAndReleases::addSelect('tags')
            ->orderBy('read', 'DESC')
            ->limit(10)
            ->get();
        $tags = "";
        foreach($tagsMoreSearchedConsult as $tagsMore){
            $tags .= $tagsMore->tags.",";
        }
        $tags = substr($tags,0,-1);
        $tags = explode(',', $tags);
        shuffle($tags);
        $arrayTags = [];
        $tagsChosen = "";
        $qtdTags = 0;
        foreach($tags as $tag){
            if(!array_search($tag, $arrayTags)){
                array_push($arrayTags, $tag);
                $tagsChosen .= $tag.",";
                $qtdTags++;
            }
            if($qtdTags == 10){
                break;
            }
        }
        $tagsChosen = substr($tagsChosen,0,-1);

        $tagsMoreSearched = NewsAndReleases::tagsList($tagsChosen, 'noticias-e-releases');

        return view('website.newsAndReleases.index')->with(compact('page', 'pages', 'websiteSettings', 'request', 'newsAndReleases', 'tagsMoreSearched'));
    }

    public function read(Request $request)
    {
        $page = 'noticias-e-releases';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();
        $date = $request->year.'-'.$request->month.'-'.$request->day;
        $newsAndReleases = NewsAndReleases::where('date', '=', $date)
            ->where('slug', '=', $request->slug)
            ->first();
        array_set($newsAndReleases, "date", Carbon::createFromFormat('Y-m-d', $newsAndReleases->date));

        //INCREMENT
        NewsAndReleases::find($newsAndReleases->newsAndReleasesId)->increment('read');

        //MORE NEWS AND RELEASES
        $moreNewsAndReleases = NewsAndReleases::orderBy('date', 'desc')
            ->where('newsAndReleasesId', '!=', $newsAndReleases->newsAndReleasesId)
            ->limit(2)
            ->addSelect('title')
            ->addSelect('date')
            ->addSelect('slug')
            ->get();
        foreach($moreNewsAndReleases as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        return view('website.newsAndReleases.intern')->with(compact('page', 'pages', 'websiteSettings', 'newsAndReleases', 'moreNewsAndReleases'));
    }
}