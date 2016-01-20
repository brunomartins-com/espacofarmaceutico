<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

use App\Texts;
use App\VisitWellPhotos;
use App\Pages;


class VisitWellController extends Controller
{
    public function index()
    {
        $page = 'visite-bem';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $text = Texts::find(20);

        return view('website.visitWell.index')->with(compact('page', 'pages', 'websiteSettings', 'text'));
    }

    public function photos(Request $request)
    {
        $page = 'visite-bem';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $years = ['' => 'Ano'];
        $consultYearMoreOld = VisitWellPhotos::orderBy('date', 'asc')->first();
        $yearMoreOld = Carbon::createFromFormat('Y-m-d', $consultYearMoreOld->date)->format('Y');
        for($i=date('Y');$i>=$yearMoreOld;$i--){
            $years[$i] = $i;
        }
        $months = ['' => 'Mês'];
        for($i=1;$i<=12;$i++){
            if($i < 10){
                $i = "0".$i;
            }
            $months[$i] = VisitWellPhotos::portugueseMonthName($i);
        }

        if(isset($request->year) and isset($request->month) and isset($request->day) and isset($request->slug)){
            $date = $request->year.'-'.$request->month.'-'.$request->day;
            $gallery = VisitWellPhotos::where('date', '=', $date)->where('slug', '=', $request->slug)->first();
        }else {
            $gallery = VisitWellPhotos::orderBy('date', 'desc')->first();
        }
        array_set($gallery, "date", Carbon::createFromFormat('Y-m-d', $gallery->date));

        $allGalleries = VisitWellPhotos::orderBy('date', 'desc')->get();
        foreach($allGalleries as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        $moreGalleries = [];
        $count = 0;
        foreach($allGalleries as $key => $galleries){
            if($galleries->visitWellPhotosId != $gallery->visitWellPhotosId){
                $count++;
                array_set($moreGalleries, $key, $galleries);
            }
            if($count == 2){
                break;
            }
        }

        return view('website.visitWell.photos')->with(compact('page', 'pages', 'websiteSettings', 'years', 'months', 'gallery', 'allGalleries', 'moreGalleries'));
    }

    public function filterPhotos(Request $request)
    {
        $page = 'visite-bem';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        $this->validate($request, [
            'year'  => 'required_without:slug',
            'month' => 'required_with:year',
            'slug'  => 'required_without:year'
        ],
        [
            'year.required_without'     => 'Você precisa buscar pela data',
            'month.required_with'       => 'Você precisa informar o mês',
            'slug.required_without'     => 'ou pela turma',
        ]);

        $years = ['' => 'Ano'];
        $consultYearMoreOld = VisitWellPhotos::orderBy('date', 'asc')->first();
        $yearMoreOld = Carbon::createFromFormat('Y-m-d', $consultYearMoreOld->date)->format('Y');
        for($i=date('Y');$i>=$yearMoreOld;$i--){
            $years[$i] = $i;
        }
        $months = ['' => 'Mês'];
        for($i=1;$i<=12;$i++){
            if($i < 10){
                $i = "0".$i;
            }
            $months[$i] = VisitWellPhotos::portugueseMonthName($i);
        }

        $gallery = VisitWellPhotos::orderBy('date', 'desc');
        if(isset($request->year) and !empty($request->year) and isset($request->month) and !empty($request->month) and empty($request->slug)) {
            $dateStart = $request->year . '-' . $request->month . '-01';
            $dateEnd = $request->year . '-' . $request->month . '-31';
            $gallery->whereBetween('date', [$dateStart, $dateEnd]);
        } else if(isset($request->slug) and !empty($request->slug) and empty($request->year) and empty($request->month)) {
            $gallery = $gallery->where('slug', '=', $request->slug);
        }else{
            return redirect('/visite-bem/fotos')->withErrors(['Você deve filtrar pela data ou pela turma!']);
        }
        $gallery = $gallery->get();
        foreach($gallery as $photo) {
            array_set($photo, "date", Carbon::createFromFormat('Y-m-d', $photo->date));
        }

        $allGalleries = VisitWellPhotos::orderBy('date', 'desc')->get();
        foreach($allGalleries as $item){
            array_set($item, "date", Carbon::createFromFormat('Y-m-d', $item->date));
        }

        return view('website.visitWell.photos')->with(compact('page', 'pages', 'websiteSettings', 'years', 'months', 'gallery', 'allGalleries', 'request'));
    }

    public function getScheduleYourVisit()
    {
        $page = 'visite-bem';
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $pages = Pages::where('slug', '=', $page)->first();

        //STATES
        $statesConsult = \App\Exceptions\Handler::readFile("states.json");
        $states = ['' => 'Estado'];
        foreach($statesConsult as $state):
            $states[$state['name']] = $state['name'];
        endforeach;

        $text = Texts::find(21);

        return view('website.visitWell.scheduleYourVisit')->with(compact('page', 'pages', 'websiteSettings', 'states', 'text'));
    }

    public function postScheduleYourVisit(Request $request)
    {
        //WEBSITE SETTINGS
        $websiteSettings = \App\Exceptions\Handler::readFile("websiteSettings.json");

        $this->validate($request, [
            'name'              => 'required|max:100',
            'email'             => 'required|email|max:40',
            'companyFoundation' => 'required|max:100',
            'state'             => 'required',
            'city'              => 'required',
            'phone'             => 'required_without:mobile',
            'mobile'            => 'required_without:phone',
            'message'           => 'required'
        ],
        [
            'name.required'             => 'Informe seu nome',
            'name.max'                  => 'O nome não pode passar de :max caracteres',
            'email.required'            => 'Informe seu e-mail',
            'email.email'               => 'Informe um e-mail válido',
            'email.max'                 => 'O e-mail não pode passar de :max caracteres',
            'companyFoundation.required'=> 'Informe a empresa/instituição',
            'companyFoundation.max'     => 'O nome da empresa/instituição não pode passar de :max caracteres',
            'state.required'            => 'Escolha seu Estado',
            'city.required'             => 'Escolha sua cidade',
            'phone.required_without'    => 'Informe um número de telefone',
            'mobile.required_without'   => 'ou um número de celular',
            'message.required'          => 'Escreva uma mensagem'
        ]);

        array_set($request, "date", Carbon::now()->format('d/m/Y'));

        Mail::send('website.visitWell.email', ['request' => $request], function ($message) use ($websiteSettings) {
            $message->from('webmaster@teuto.com.br', 'Teuto/Pfizer')
                ->subject('Agende sua Visita - Visite Bem')
                ->to('treinamentomkt@teuto.com.br')
                ->to('hello@brunomartins.com');
        });

        $success = "E-mail enviado com sucesso!";
        return redirect(url('visite-bem/agende-sua-visita'))->with(compact('success'));
    }
}