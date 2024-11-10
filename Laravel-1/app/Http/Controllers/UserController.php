<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        return response()->json(['message' => 'User Created', 'data' => $user], 200);
    }


    public function show($id)
    {
        $user= User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'El usuario no existe', 'data' => []], 404);
        }
        return response()->json(['message' => 'Usuario encontrado.', 'data' => $user], 200);
    }

    //OTRA FORMA

//    public function show(User $user)
//    {
//        return response()->json(['message' => '', 'data' => $user], 200);
//    }


    public function show_address($id)
    {
        $user = User::with('address')->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'El usuario con dicha dirección no existe.', 'data' => []], 404);
        }
        return response()->json(['message' => 'Usuario encontrado.', 'data' => $user->address], 200);
    }

//OTRA FORMA

//    public function show_address(User $user)
//    {
//        return response()->json(['message' => '', 'data' => $user->address], 200);
//    }


    public function bookEvent(Request $request, User $user, Event $event)
    {
        $note = '';
        if($request->get('note')){
            $note = $request->get('note');
        }
        if($user->events()->save($event, array('note' => $note))){
            return response()->json(['message'=>'User Event Created','data'=>array($event, 'note'=>$note)],200);
        }
        return response()->json(['message'=>'Error','data'=>null],400);
    }



    public function listEvents($id)
    {
        $user = User::with('events')->find($id);
        if (!$user) {
            return response()->json(['message' => 'El usuario no existe.', 'data' => []], 404);
        }
        if ($user->events->isEmpty()) {
            return response()->json(['message' => 'El usuario no tiene eventos asociados.', 'data' => []], 404);
        }
        return response()->json(['message' => 'Eventos encontrados para ususario.', 'data' => $user->events], 200);
    }

//OTRA FORMA

//    public function listEvents(User $user)
//    {
//        $events = $user->events;
//        return response()->json(['message' => null, 'data' => $events], 200);
//    }


    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $user->events()->detach();
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }


    public function updateUser(Request $request, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $name = $request->query('name', $user->name);
        $email = $request->query('email', $user->email);
        $password = $request->query('password');
        $user->name = $name;
        $user->email = $email;
        if ($password) {
            $user->password = bcrypt($password);
        }
        $user->save();
        return response()->json(['message' => 'Usuario actualizado correctamente', 'user' => $user]);
    }


}
