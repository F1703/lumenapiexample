<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        //desde postman en el header: Content-Type: application/json
        if ($request->isJson()) {
            $users = User::all();
            // $users = [
            //     'name'     => 'franco',
            //     'username' => 'fran',
            //     'email'    => 'fran@gmail.com',
            // ];
            return response()->json($users, 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401, []);
    }

    public function createUser(Request $request)
    {
        if ($request->isJson()) {
            $data = $request->json()->all();
            if (!empty($data)) {
                $user = User::create([
                    'name'      => $data['name'],
                    'username'  => $data['username'],
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password']),
                    'api_token' => str_random(60),
                ]);
                return response()->json($user, 200);
            }
            return response()->json(['error' => 'datos incorrectos'], 404);

        }
        return response()->json(['error' => 'Unauthorized'], 401, []);
    }

    public function getToken(Request $request)
    {

        if ($request->isJson()) {
            try {
                $data = $request->json()->all();
                $user = User::where('username', $data['username'])->first();
                if ($user && Hash::check($data['password'], $user->password)) {
                    return response()->json($user, 200);
                } else {
                    return response()->json(['error' => 'No content'], 406);
                }

            } catch (ModeNotFoundException $e) {
                return response()->json(['error' => 'No contentasddsf'], 406);
            }
            return response()->json(['error' => 'No content _asdfasf'], 406);
        }
        return response()->json(['error' => 'Unauthorized'], 401, []);
    }
}
