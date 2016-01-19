<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Products;
use App\ProductsCategories;
use App\Pages;


class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $page = 'produtos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $productsCategories = ProductsCategories::orderBy('sortorder', 'asc')->get();

        $activePrinciples = Products::addSelect(DB::raw('DISTINCT(activePrinciple)'))
            ->where('activePrinciple', '!=', '')
            ->orderBy('activePrinciple', 'asc')
            ->get();
        foreach($activePrinciples as $activePrinciple){
            array_add($activePrinciple, 'activePrincipleSlug', str_slug($activePrinciple->activePrinciple, '-'));
        }

        $products = Products::orderBy('name', 'asc');

        $categoryChosen = "";
        $categoryChosenSlug = "";
        if(isset($request->slug)){
            $productsCategory = ProductsCategories::where('productsCategoriesSlug', '=', $request->slug)->first();
            if(!empty($productsCategory->productsCategoriesId)) {
                $categoryChosen = $productsCategory->productsCategoriesName;
                $categoryChosenSlug = $request->slug;
                $products = $products->where('productsCategoriesId', '=', $productsCategory->productsCategoriesId);
            }
        }

        if(isset($request->activePrincipleSlug)){
            $products = $products->where('activePrinciple', 'LIKE', str_replace('-', ' ', $request->activePrincipleSlug));
        }

        $activePrincipleChosenSlug = isset($request->activePrincipleSlug) ? $request->activePrincipleSlug : null;

        $products = $products->paginate(15);

        return view('website.products.index')->with(compact('page', 'pages', 'websiteSettings', 'productsCategories', 'products', 'categoryChosen', 'categoryChosenSlug', 'activePrinciples', 'activePrincipleChosenSlug'));
    }

    public function search(Request $request)
    {
        if(!isset($request->keywords)){
            return redirect(url('produtos'));
        }
        $keywordsSearched = isset($request->keywords) ? $request->keywords : null;

        $page = 'produtos';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $productsCategories = ProductsCategories::orderBy('sortorder', 'asc')->get();

        $activePrinciples = Products::addSelect(DB::raw('DISTINCT(activePrinciple)'))
            ->where('activePrinciple', '!=', '')
            ->orderBy('activePrinciple', 'asc')
            ->get();
        foreach($activePrinciples as $activePrinciple){
            array_add($activePrinciple, 'activePrincipleSlug', str_slug($activePrinciple->activePrinciple, '-'));
        }

        $products = Products::orderBy('name', 'asc')
            ->where('name', 'LIKE', '%'.$request->keywords.'%')
            ->get();

        return view('website.products.index')->with(compact('page', 'pages', 'websiteSettings', 'productsCategories', 'products', 'activePrinciples', 'keywordsSearched'));
    }
}