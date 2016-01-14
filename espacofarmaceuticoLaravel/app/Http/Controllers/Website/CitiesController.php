<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Cities;

class CitiesController extends Controller
{
    public function post(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'state' => 'required|max:2|min:2'
        ]);

        $cities = "";
        if(!$validation->fails()) {
            $cities = Cities::where('uf', $request->state)->get();
        }

        return json_encode($cities);
    }
}