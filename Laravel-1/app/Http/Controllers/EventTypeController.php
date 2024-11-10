<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventTypeController extends Controller
{
    function store(Request $request){
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $eventType = EventType::create([
            'description' => $request->get('description'),
        ]);
        return response()->json(['message' => 'Event type created', 'data' => $eventType], 200);
    }

    public function listEvents($id)
    {
        $type = EventType::with('events')->find($id);
        if (!$type) {
            return response()->json([
                'message' => 'El tipo de evento seleccionado no existe.', 'data' => []], 404);
        }
        if (count($type->events) === 0) {
            return response()->json([
                'message' => 'No hay eventos para este tipo.', 'data' => []], 404);
        }
        return response()->json([
            'message' => 'Tipo de evento encontrado',
            'data' => $type->events
        ], 200);
    }

    //OTRA OPCIÓN

    //public function listEvents(EventType $type){
    //    $events = $type->events;
    //    return response()->json(['message'=>null,'data'=>$events],200);
    //}
}
