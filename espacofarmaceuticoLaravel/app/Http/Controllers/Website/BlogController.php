<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Blog;
use App\Pages;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $page = 'blog';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();
        $blog = Blog::orderBy('date', 'desc');
        if(!empty($request->palavra)){
            $blog = $blog->where('tags', 'LIKE', '%'.$request->palavra.'%');
        }
        $blog = $blog->paginate(4);
        if(!empty($request->palavra)) {
            $blog->setPath('busca?palavra=' . $request->palavra . '&');
        }
        foreach($blog as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        $tagsMoreSearchedConsult = Blog::addSelect('tags')
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

        $tagsMoreSearched = Blog::tagsList($tagsChosen, 'blog');

        return view('website.blog.index')->with(compact('page', 'pages', 'websiteSettings', 'request', 'blog', 'tagsMoreSearched'));
    }

    public function read(Request $request)
    {
        $page = 'blog';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();
        $date = $request->year.'-'.$request->month.'-'.$request->day;
        $blog = Blog::where('date', '=', $date)
            ->where('slug', '=', $request->slug)
            ->first();
        array_set($blog, "date", Carbon::createFromFormat('Y-m-d', $blog->date));

        //INCREMENT
        Blog::find($blog->blogId)->increment('read');

        //MORE BLOG
        $moreBlog = Blog::orderBy('date', 'desc')
            ->where('blogId', '!=', $blog->blogId)
            ->limit(2)
            ->addSelect('title')
            ->addSelect('date')
            ->addSelect('slug')
            ->get();
        foreach($moreBlog as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        return view('website.blog.intern')->with(compact('page', 'pages', 'websiteSettings', 'blog', 'moreBlog'));
    }
}