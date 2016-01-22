<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Exceptions\Handler;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use League\Flysystem\Filesystem;
use Carbon\Carbon;

use App\WebsiteSettings;

class WebsiteSettingsController extends Controller
{
    public $folder;
    public $websiteSettingsId;
    public $faviconWidth;
    public $faviconHeight;
    public $avatarWidth;
    public $avatarHeight;
    public $appleTouchIconWidth;
    public $appleTouchIconHeight;

    public function __construct(){
        $this->folder               = "assets/images/_upload/websiteSettings/";
        $this->websiteSettingsId    = 1;
        $this->faviconWidth         = 48;
        $this->faviconHeight        = 48;
        $this->avatarWidth          = 230;
        $this->avatarHeight         = 230;
        $this->appleTouchIconWidth  = 129;
        $this->appleTouchIconHeight = 129;

    }
    public function getIndex()
    {
        if (! ACL::hasPermission('websiteSettings', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para acessar os dados do site.']);
        }

        $imageDetails = [
            'folder'                => $this->folder,
            'faviconWidth'          => $this->faviconWidth,
            'faviconHeight'         => $this->faviconHeight,
            'avatarWidth'           => $this->avatarWidth,
            'avatarHeight'          => $this->avatarHeight,
            'appleTouchIconWidth'   => $this->appleTouchIconWidth,
            'appleTouchIconHeight'  => $this->appleTouchIconHeight
        ];

        $websiteSettings = WebsiteSettings::where('websiteSettingsId', '=', $this->websiteSettingsId)->first();

        return view('admin.websiteSettings.index')->with(compact('websiteSettings', 'imageDetails'));
    }

    public function putUpdate(Request $request)
    {

        if (! ACL::hasPermission('websiteSettings', 'edit')) {
            return redirect(route('home'))->withErrors(['Você não tem permissão para editar os dados do site.']);
        }

        $this->validate($request, [
            'title'                 => 'required|max:50',
            'costumerServicePhone'  => 'required',
            'address'               => 'required',
            'email'                 => 'required|email|max:50'
        ],
        [
            'title.required'                => 'Informe o título do site',
            'title.max'                     => 'O título do site não pode ter mais de :max caracteres',
            'costumerServicePhone.required' => 'Informe o telefone SAC',
            'address.required'              => 'Informe o endereço',
            'email.required'                => 'Informe o e-mail padrão do website',
            'email.email'                   => 'Informe um e-mail válido',
            'email.email'                   => 'O e-mail não pode passar de :max caracteres'
        ]);

        $websiteSettings = WebsiteSettings::find($this->websiteSettingsId);
        $websiteSettings->title                 = $request->title;
        $websiteSettings->costumerServicePhone  = $request->costumerServicePhone;
        $websiteSettings->phone                 = $request->phone;
        $websiteSettings->address               = $request->address;
        $websiteSettings->email                 = $request->email;
        $websiteSettings->facebook              = $request->facebook;
        $websiteSettings->twitter               = $request->twitter;
        $websiteSettings->youtube               = $request->youtube;
        $websiteSettings->instagram             = $request->instagram;
        $websiteSettings->linkedin              = $request->linkedin;
        $websiteSettings->googleAnalytics       = $request->googleAnalytics;
        $websiteSettings->websiteOk             = $request->websiteOk;

        if ($request->favicon) {
            //DELETE OLD FAVICON
            if($request->currentFavicon != ""){
                if(File::exists($this->folder.$request->currentFavicon)){
                    File::delete($this->folder.$request->currentFavicon);
                }
            }
            $extension = $request->favicon->getClientOriginalExtension();
            $nameFavicon = "favicon.".$extension;

            Image::make($request->file('favicon'))->resize($this->faviconWidth, $this->faviconHeight)->save($this->folder.$nameFavicon);

            $websiteSettings->favicon = $nameFavicon;
        }
        if ($request->avatar) {
            //DELETE OLD AVATAR
            if($request->currentAvatar != ""){
                if(File::exists($this->folder.$request->currentAvatar)){
                    File::delete($this->folder.$request->currentAvatar);
                }
            }
            $extension = $request->avatar->getClientOriginalExtension();
            //$nameAvatar = Carbon::now()->format('YmdHis').".".$extension;
            $nameAvatar = "avatar.".$extension;

            $img = Image::make($request->file('avatar'));
            if($request->avatarCropAreaW > 0 or $request->avatarCropAreaH > 0 or $request->avatarPositionX or $request->avatarPositionY){
                $img->crop($request->avatarCropAreaW, $request->avatarCropAreaH, $request->avatarPositionX, $request->avatarPositionY);
            }
            $img->resize($this->avatarWidth, $this->avatarHeight)->save($this->folder.$nameAvatar);

            $websiteSettings->avatar = $nameAvatar;
        }
        if ($request->appleTouchIcon) {
            //DELETE OLD APPLE TOUCH ICON
            if($request->currentAppleTouchIcon != ""){
                if(File::exists($this->folder.$request->currentAppleTouchIcon)){
                    File::delete($this->folder.$request->currentAppleTouchIcon);
                }
            }
            $extension = $request->appleTouchIcon->getClientOriginalExtension();
            $nameAppleTouchIcon = "apple-touch-icon.".$extension;

            Image::make($request->file('appleTouchIcon'))->resize($this->appleTouchIconWidth, $this->appleTouchIconHeight)->save($this->folder.$nameAppleTouchIcon);

            $websiteSettings->appleTouchIcon = $nameAppleTouchIcon;
        }

        //WRITE JSON
        Handler::writeFile("websiteSettings.json", json_encode($websiteSettings));

        $websiteSettings->save();

        $success = "Dados do site editados com sucesso!";

        return redirect(route('websiteSettings'))->with(compact('success'));

    }
}