<?php namespace App\Http\Controllers\Admin;

use App\ACL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Events;

class EventsController extends Controller
{
    public function getIndex()
    {
        if (! ACL::hasPermission('visitWellPhotos')) {
            return redirect(route('home'))->withErrors(['Você não pode acessar a página Eventos.']);
        }

        $events = Events::orderBy('date', 'DESC')->get();
        foreach($events as $event){
            array_set($event, 'date', Carbon::createFromFormat('Y-m-d', $event->date)->format('d/m/Y'));
        }

        return view('admin.events.index')->with(compact('events'));
    }

    public function getAdd()
    {
        if (! ACL::hasPermission('events', 'add')) {
            return redirect(route('events'))->withErrors(['Você não pode adicionar Eventos.']);
        }

        return view('admin.events.add');
    }

    public function postAdd(Request $request)
    {
        if (! ACL::hasPermission('events', 'add')) {
            return redirect(route('events'))->withErrors(['Você não pode adicionar eventos.']);
        }

        $this->validate($request, [
            'type'      => 'required',
            'date'      => 'required|max:10',
            'title'     => 'required|max:100',
            'details'   => 'required'
        ],
        [
            'type.required'     => 'Escolha o tipo do evento',
            'date.required'     => 'Informe a data do evento',
            'date.max'          => 'A data não pode passar de :max caracteres',
            'title.required'    => 'Informe o título do evento',
            'title.max'         => 'O título do evento não pode passar de :max caracteres',
            'details.required'  => 'Informe os detalhes do evento'
        ]);

        $events = new Events();
        $events->type       = $request->type;
        $events->date       = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $events->title      = $request->title;
        $events->details    = $request->details;

        $events->save();

        $success = "Evento adicionado com sucesso.";

        return redirect(route('events'))->with(compact('success'));

    }

    public function getEdit($eventsId)
    {
        if (! ACL::hasPermission('events', 'edit')) {
            return redirect(route('events'))->withErrors(['Você não pode editar eventos.']);
        }

        $event = Events::find($eventsId);
        array_set($event, 'date', Carbon::createFromFormat('Y-m-d', $event->date)->format('d/m/Y'));

        return view('admin.events.edit')->with(compact('event'));
    }

    public function putEdit(Request $request)
    {
        if (! ACL::hasPermission('events', 'edit')) {
            return redirect(route('events'))->withErrors(['Você não pode editar eventos.']);
        }

        $this->validate($request, [
            'type'      => 'required',
            'date'      => 'required|max:10',
            'title'     => 'required|max:100',
            'details'   => 'required'
        ],
        [
            'type.required'     => 'Escolha o tipo do evento',
            'date.required'     => 'Informe a data do evento',
            'date.max'          => 'A data não pode passar de :max caracteres',
            'title.required'    => 'Informe o título do evento',
            'title.max'         => 'O título do evento não pode passar de :max caracteres',
            'details.required'  => 'Informe os detalhes do evento'
        ]);

        $events = Events::find($request->eventsId);
        $events->type       = $request->type;
        $events->date       = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $events->title      = $request->title;
        $events->details    = $request->details;

        $events->save();

        $success = "Evento editado com sucesso";

        return redirect(route('events'))->with(compact('success'));

    }

    public function delete(Request $request)
    {
        if (! ACL::hasPermission('events', 'delete')) {
            return redirect(route('events'))->withErrors(['Você não pode deletar Eventos.']);
        }

        Events::find($request->get('eventsId'))->delete();

        $success = "Evento excluído com sucesso.";

        return redirect(route('events'))->with(compact('success'));
    }
}