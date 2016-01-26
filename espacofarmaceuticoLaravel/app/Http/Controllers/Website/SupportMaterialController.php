<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Apps;
use App\Pages;

class SupportMaterialController extends Controller
{
    public function apps()
    {
        $page = 'material-de-apoio';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $apps = Apps::orderBy('sortorder', 'asc')
            ->paginate(4);

        return view('website.supportMaterial.apps')->with(compact('page', 'pages', 'websiteSettings', 'apps'));
    }

    public function imcCalculator(Request $request)
    {
        $page = 'material-de-apoio';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $imc = "";
        if($request->method() == "POST") {
            $this->validate($request, [
                'height'        => 'required|max:4',
                'weight'        => 'required|max:6'
            ],
            [
                'height.required'   => 'Informe a sua altura',
                'height.max'        => 'A altura não pode passar de :max caracteres',
                'weight.required'   => 'Informe o seu peso',
                'weight.max'        => 'O peso não pode passar de :max caracteres'
            ]);

            $weight = str_replace(',', '.', $request->weight);
            $height = str_replace(',', '.', $request->height);
            $imc = intval($weight / ($height * $height));
        }

        return view('website.supportMaterial.imcCalculator')->with(compact('page', 'pages', 'websiteSettings', 'imc'));
    }
}