<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Events;
use App\Pages;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $page = 'eventos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $type = 0;
        if(!empty($request->type) and $request->type == "internacionais"){
            $type = 1;
        }
        $events = Events::orderBy('date', 'desc')
            ->where('type', '=', $type)
            ->paginate(5);

        return view('website.events.index')->with(compact('page', 'pages', 'websiteSettings', 'request', 'events'));
    }
}