<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    function index()
    {
        return Reservation::all();
    }

    function searchByID($id)
    {
        return Reservation::find($id);
    }

    function create(Request $request)
    {

        // was the API Token set?
        if ($request->api_token) {
            $user = \App\Models\User::query()->where("api_token", "=", $request->api_token)->get();

            // is the API Token from a valid user?
            if (count($user) > 0) {
                $user = $user[0];

                // check if the room is reserved on the date.
                if (
                    Reservation::query()
                        ->where("date", "=", $request->date_iso)
                        ->where("room_id", "=", $request->room_id)
                        ->get()->count() == 0
                ) {
                    $reservation = new Reservation();

                    $reservation->date = $request->date_iso;
                    $reservation->user_id = $user->id;
                    $reservation->room_id = $request->room_id;

                    $reservation->save();

                    return $reservation;
                }

                return response('Error, Rooms already reserved on this date', 406);
            }
        }

        return response('Unauthenticated, Please provide API Token.', 401);
    }

    function getUserReservations(Request $request) {
        if (!isset($request->api_token)) {
            return response('Error, no API Token given', 401);
        }

        $user = \App\Models\User::query()->where("api_token", "=", $request->api_token)->get();

        if ($user->count() == 0) {
            return response('Error, no user bound to this API Token', 401);
        }

        $user = $user[0];

        return Reservation::query()->where("user_id", "=", $user->id)->get();
    }

    function update(Request $request) {
        if (!isset($request->api_token)) {
            return response('Error, no API Token given', 401);
        }

        $user = \App\Models\User::query()->where("api_token", "=", $request->api_token)->get();

        if ($user->count() == 0) {
            return response('Error, no user bound to this API Token', 401);
        }

        $user = $user[0];

        if (isset($request->reservation_id)) {
            $reservation = Reservation::find($request->reservation_id);
            $reservationUser = $reservation->user()->get()[0];

            if ($reservationUser->api_token != $user->api_token) {
                return response('Error, reservation belongs to different API Token.', 401);
            }

            if (isset($request->date)) {
                $reservation->date = $request->date;
            }

            if (isset($request->room_id)) {
                $reservation->room_id = $request->room_id;
            }

            $reservation->save();

            return response('Success, The reservation has changed.', 202);

        }

        return response('Error, no reservation ID provided.', 401);
    }

    function delete(Request $request)
    {

        if (!isset($request->api_token)) {
            return response('Error, no API Token given', 401);
        }

        $user = \App\Models\User::query()->where("api_token", "=", $request->api_token)->get();

        if ($user->count() == 0) {
            return response('Error, no user bound to this API Token', 401);
        }

        $user = $user[0];

        if (isset($request->reservation_id)) {
            $reservation = Reservation::find($request->reservation_id);
            $reservationUser = $reservation->user()->get()[0];

            if ($reservationUser->api_token != $user->api_token) {
                return response('Error, reservation belongs to different API Token.', 401);
            }

            $reservation->delete();
        }

        return response('Success, the reservation was deleted.', 200);
    }
}
