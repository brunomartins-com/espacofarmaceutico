<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\User;

class ProfileController extends Controller
{
    public function putUpdate(Request $request)
    {
        $userVerify = 0;
        if($request->inputName == 'email' or $request->inputName == 'cpf'){
            $userVerify = User::where('type', '=', 1)
                ->where('id', '!=', $request->id)
                ->where($request->inputName, '=', $request->inputValue)
                ->count();
        }

        if($userVerify > 0){
            $var = ['success' => 0, 'message' => 'O '.$request->inputName.' que você inseriu já está sendo usado por outro usuário!'];
        }else {
            if($request->inputName == 'babyBirthdate'){
                $babyBirthdate = Carbon::createFromFormat('d/m/Y', $request->inputValue)->format('Y-m-d');
                $user = User::where('id', $request->id)->update([$request->inputName => $babyBirthdate]);
            }else {
                $user = User::where('id', $request->id)->update([$request->inputName => $request->inputValue]);
            }

            if ($user) {
                $var = ['success' => 1, 'newInputValue' => $request->inputValue];
            } else {
                $var = ['success' => 0, 'newInputValue' => $request->inputValue];
            }
        }

        return json_encode($var);
    }
}