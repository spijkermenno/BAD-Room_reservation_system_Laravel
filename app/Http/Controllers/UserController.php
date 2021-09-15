<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    function index(Request $request) {
        if (!isset($request->api_token)) {
            return response('Error, no API Token given', 401);
        }

        $user = \App\Models\User::query()->where("api_token", "=", $request->api_token)->get();

        if ($user->count() == 0) {
            return response('Error, no user bound to this API Token', 401);
        }

        $user = $user[0];

        return $user;
    }

    function signin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->api_token == "") {
                Auth::user()->generateToken();
            }
            return Auth::user()->api_token;
        }

        return response('Error, email not found', 400);

    }

    function register(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (User::query()->where("email", "=", $request->email)->get()->count() == 0) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->name);

            $user->save();
            $user->generateToken();

            return $user;
        }

        return response('Error, email already registered', 406);
    }

    function update(Request $request)
    {
        if (!isset($request->api_token)) {
            return response('Error, no API Token given', 401);
        }

        $user = User::query()->where("api_token", "=", $request->api_token)->get();

        if ($user->count() == 0) {
            return response('Error, no user bound to this API Token', 401);
        }

        $user = $user[0];

        if (isset($request->name)) {
            $user->name = $request->name;
        }

        if (isset($request->email)) {
            $user->email = $request->email;
        }

        if (isset($request->password)) {
            $user->password = Hash::make($request->name);
        }

        $user->save();
        $user->generateToken();

        return response('Success, your API Token was changed by this event. Please retrieve a new API Token by signing in.', 202);
    }

    function delete(Request $request)
    {

        if (!isset($request->api_token)) {
            return response('Error, no API Token given', 401);
        }

        $user = User::query()->where("api_token", "=", $request->api_token)->get();

        if ($user->count() == 0) {
            return response('Error, no user bound to this API Token', 401);
        }

        $user = $user[0];

        $user->delete();

        return response('Success, the user was deleted.', 200);
    }
}
