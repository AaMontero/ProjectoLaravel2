<?php

namespace App\Http\Controllers;


use App\Models\Eventos;
use Illuminate\Http\Request;


class CalendarController extends Controller
{
    //
    public function index(){
        $events = array();
        $eventos = Eventos::all();
        foreach($eventos as $evento){
            $events[] = [
                'id' => $evento->id,
                'title' => $evento->title,
                'start' => $evento->start_date,
                'end' => $evento->end_date,
                'author' => $evento->author,
                'note' => $evento->note,
            ];
        }

        return view ('calendar.calendar',['event' => $events]);
    }

    public function store(Request $request){
        $request->validate([

            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'author' => 'required|string',
            'note' => 'required|string',

        ]);

        $eventos = Eventos::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'author' => $request->author,
            'note' => $request->note,
            'user_id' => auth()->id()

        ]);
        return response()->json($eventos);
    }

    public function update(Request $request ,$id){

        $eventos = Eventos::find($id);
            if(!$eventos){
                return response()->json(['error'=>'No se pudo encontrar el evento'],404);
            }

            $eventos->update([
                'title' => $request->title,
                'author' => $request->author,
                'note' => $request->note,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,

            ]);
            return response()->json(['message' => 'Evento actualizado correctamente', 'event' => $eventos]);
    }

    public function destroy($id){
        $eventos = Eventos::find($id);
        if(! $eventos){
            return response()->json(['error' => 'No se pudo encontrar el evento'], 404);
        }
            $eventos->delete();
            return response()->json(['message' => 'Evento eliminado correctamente', 'id' => $id]);
        }


}
