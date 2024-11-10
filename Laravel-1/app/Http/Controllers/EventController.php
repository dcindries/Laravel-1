<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{


    public function listUsers($id)
    {
        $event = Event::with('users')->find($id);
        if (!$event) {
            return response()->json(['message' => 'El evento especificado no fue encontrado.', 'data' => []], 404);
        }
        if ($event->users->isEmpty()) {
            return response()->json(['message' => 'El evento no tiene usuarios asociados.', 'data' => []], 404);
        }
        return response()->json(['message' => 'Usuarios encontrados.', 'data' => $event->users], 200);
    }

//OTRA FORMA
//    public function listUsers(Event $event)
//    {
//        $users = $event->users;
//        return response()->json(['message'=>null,'data'=>$users],200);
//    }





    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_name' => 'required|string|max:255',
            'event_detail' => 'required|string|max:255',
            'event_type_id' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $event = Event::create([
            'event_name' => $request->get('event_name'),
            'event_detail' => $request->get('event_detail'),
            'event_type_id' => $request->get('event_type_id'),
        ]);

        return response()->json(['message' => 'Event created', 'data' => $event], 200);
    }
}
