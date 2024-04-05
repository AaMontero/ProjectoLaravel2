<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Eventos;
use App\Models\UserAction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Cliente;


class CalendarController extends Controller
{
    //
    public function index()
    {
        $events = array();
        $eventos = Eventos::all();
        $hoteles = Hotel::pluck('hotel_nombre');
        $clientes = Cliente::select('id','nombres', 'apellidos','cedula')->get();

        foreach ($eventos as $evento) {
            $events[] = [
                'id' => $evento->id,
                'title' => $evento->title,
                'start' => $evento->start_date,
                'end' => $evento->end_date,
                'cliente_nombres' => $evento->cliente->nombres,
                'cliente_apellidos' => $evento->cliente->apellidos,
                'cliente_cedula'=> $evento->cliente->cedula,
                'cliente_id' => $evento->cliente_id,
                'hotel_nombre' => $evento->hotel_nombre,
            ];
        }

        return view('calendar.calendar', ['event' => $events, 'hoteles' => $hoteles, 'clientes' => $clientes]);
    }

    public function store(Request $request)
    {
        $request->validate([

            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'hotel_nombre' => 'required|string',

        ]);


        $eventos = Eventos::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'cliente_id' => $request->cliente_id,
            'hotel_nombre' => $request->hotel_nombre,
            'user_id' => auth()->id()

        ]);

        // Crea un registro en la tabla UserAction
        UserAction::create([
            'user_id' => auth()->id(),
            'action' => 'crear', // Acción de creación
            'entity_type' => 'evento', // Tipo de entidad
            'entity_id' => $eventos->id, // ID del evento creado
        ]);
        return response()->json($eventos);
    }


    public function update(Request $request, $id)
    {
        $eventos = Eventos::find($id);
        if (!$eventos) {
            return response()->json(['error' => 'No se pudo encontrar el evento'], 404);
        }

        // Obtener los datos originales del evento antes de la actualización
        $originalData = $eventos->getAttributes();

        // Actualizar el evento en la base de datos
        $eventos->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'cliente_id' => $request->cliente_id,
            'hotel_nombre' => $request->hotel_nombre,
        ]);

        // Obtener los datos modificados del evento
        $modifiedData = [];
        foreach ($originalData as $key => $value) {
            if ($key == 'start_date' || $key == 'end_date') {
                // Convertir las fechas al formato ISO 8601 para la comparación
                $originalDate = $value ? Carbon::parse($value)->toISOString() : null;
                $newDate = $eventos->{$key} ? Carbon::parse($eventos->{$key})->toISOString() : null;
                if ($originalDate != $newDate) {
                    $modifiedData[$key] = $newDate;
                }
            } elseif ($value != $eventos->{$key}) {
                $modifiedData[$key] = $eventos->{$key};
            }
        }

        // Convierte los datos modificados a JSON
        $modifiedDataJson = json_encode($modifiedData);

        // Crear un registro en la tabla UserAction
        UserAction::create([
            'user_id' => auth()->id(),
            'action' => 'editar', // Acción de actualización
            'entity_type' => 'evento', // Tipo de entidad
            'entity_id' => $eventos->id, // ID del evento actualizado
            'modified_data' => $modifiedDataJson, // Datos modificados
            // Otros campos relevantes que desees registrar en el log
        ]);

        return response()->json(['message' => 'Evento actualizado correctamente', 'event' => $eventos]);
    }

    public function destroy($id)
    {
        $eventos = Eventos::find($id);
        if (!$eventos) {
            return response()->json(['error' => 'No se pudo encontrar el evento'], 404);
        }

        // Crear un registro en la tabla UserAction antes de eliminar el evento
        UserAction::create([
            'user_id' => auth()->id(),
            'action' => 'elimiar', // Acción de eliminación
            'entity_type' => 'evento', // Tipo de entidad
            'entity_id' => $id, // ID del evento eliminado
            'modified_data' => json_encode($eventos), // Datos del evento eliminado
        ]);

        $eventos->delete();
        return response()->json(['message' => 'Evento eliminado correctamente', 'id' => $id]);
    }
}

