<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Newsletter;

class NewsletterController extends Controller
{
    public function post(Request $request)
    {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter|max:40'
        ]);

        $array = [];
        if(!$validation->fails()) {
            $newsletterConsult = Newsletter::where('email', $request->email)->count();
            if ($newsletterConsult == 0) {
                $newsletter = new Newsletter();
                $newsletter->email = $request->email;
                $newsletter->save();

                $array = ['error' => 0, 'msg' => 'E-mail cadastrado com sucesso!'];
            } else {
                $array = ['error' => 1, 'msg' => 'E-mail já cadastrado anteriormente!'];
            }
        }else{
            $array = ['error' => 1, 'msg' => 'Erro ao cadastrar o e-mail!'];
        }

        return json_encode($array);
    }
}